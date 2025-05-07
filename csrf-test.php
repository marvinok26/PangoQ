<?php
/**
 * CSRF Token Diagnostic and Fix Tool
 * 
 * This script should be placed in your Laravel project's root directory and run via CLI:
 * php csrf-test.php
 */

// Load Laravel's bootstrap file to access the application
require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting CSRF Token Diagnostic...\n\n";

// Check application key
$appKey = config('app.key');
if (empty($appKey) || $appKey === 'SomeRandomString') {
    echo "❌ APP_KEY is missing or not properly set.\n";
    echo "   Run 'php artisan key:generate' to fix this issue.\n\n";
} else {
    echo "✅ APP_KEY is properly set.\n\n";
}

// Check session configuration
echo "Session Configuration:\n";
echo "- Driver: " . config('session.driver') . "\n";
echo "- Lifetime: " . config('session.lifetime') . " minutes\n";
echo "- Path: " . config('session.path') . "\n";
echo "- Domain: " . (config('session.domain') ?: 'null') . "\n";
echo "- Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "- HTTP Only: " . (config('session.http_only') ? 'true' : 'false') . "\n";
echo "- Same Site: " . config('session.same_site') . "\n\n";

// Check CSRF token settings
$excludedUris = app('Illuminate\Foundation\Http\Middleware\VerifyCsrfToken')->except;
echo "CSRF Token Configuration:\n";
echo "- Excluded URIs: " . (!empty($excludedUris) ? implode(', ', $excludedUris) : 'None') . "\n\n";

// Check Redis configuration if using Redis for sessions
if (config('session.driver') === 'redis') {
    echo "Redis Configuration:\n";
    echo "- Host: " . config('database.redis.default.host') . "\n";
    echo "- Port: " . config('database.redis.default.port') . "\n";
    echo "- Password: " . (config('database.redis.default.password') ? '[Set]' : '[Not Set]') . "\n";
    
    // Test Redis connection
    try {
        $redis = new Redis();
        $connected = $redis->connect(
            config('database.redis.default.host'), 
            config('database.redis.default.port'),
            1.5
        );
        
        if ($connected) {
            if (config('database.redis.default.password')) {
                $redis->auth(config('database.redis.default.password'));
            }
            
            echo "✅ Redis connection successful\n\n";
            
            // Test session storage
            $sessionId = \Illuminate\Support\Str::random(40);
            $redis->set('laravel:' . $sessionId, 'test');
            $result = $redis->get('laravel:' . $sessionId);
            
            if ($result === 'test') {
                echo "✅ Redis session storage test successful\n\n";
            } else {
                echo "❌ Redis session storage test failed\n\n";
            }
            
            $redis->del('laravel:' . $sessionId);
        } else {
            echo "❌ Redis connection failed\n\n";
        }
    } catch (Exception $e) {
        echo "❌ Redis error: " . $e->getMessage() . "\n\n";
    }
}

// Run fixes
echo "Applying fixes...\n\n";

// Fix 1: Add auth.php file with customized session handling
$authFilePath = __DIR__ . '/routes/auth.php';
$authFileContent = <<<'EOD'
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Custom fix for CSRF token issues
Route::middleware(['web'])->group(function () {
    // Force session start on auth routes
    Route::middleware(function ($request, $next) {
        if (!Session::isStarted()) {
            Session::start();
        }
        return $next($request);
    })->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('register', function () {
                // Ensure CSRF token is generated
                csrf_token();
                return view('auth.register');
            })->name('register');
            
            Route::post('register', [RegisteredUserController::class, 'store']);
            
            Route::get('login', function () {
                // Ensure CSRF token is generated
                csrf_token();
                return view('auth.login');
            })->name('login');
            
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
            
            Route::get('forgot-password', function () {
                return view('auth.forgot-password');
            })->name('password.request');
            
            Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
            
            Route::get('reset-password/{token}', function (string $token) {
                return view('auth.reset-password', ['token' => $token]);
            })->name('password.reset');
            
            Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
            
            // Social Authentication Routes
            Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
                ->name('auth.provider')
                ->where('provider', 'google|facebook');
            
            Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
                ->withoutMiddleware(['csrf'])
                ->name('auth.callback')
                ->where('provider', 'google|facebook');
            
            // Named routes for convenience
            Route::get('auth/google', [SocialAuthController::class, 'redirectToProvider'])
                ->name('auth.google')
                ->defaults('provider', 'google');
                
            Route::get('auth/facebook', [SocialAuthController::class, 'redirectToProvider'])
                ->name('auth.facebook')
                ->defaults('provider', 'facebook');
        });

        Route::middleware('auth')->group(function () {
            Route::get('verify-email', function () {
                return view('auth.verify-email');
            })->name('verification.notice');
            
            Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
            
            Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['throttle:6,1'])
                ->name('verification.send');
            
            Route::get('confirm-password', function () {
                return view('auth.confirm-password');
            })->name('password.confirm');
            
            Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
            
            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
        });
    });
});
EOD;

file_put_contents($authFilePath, $authFileContent);
echo "✅ Created updated auth.php with CSRF fixes.\n";

// Fix 2: Update VerifyCsrfToken middleware
$verifyCsrfPath = __DIR__ . '/app/Http/Middleware/VerifyCsrfToken.php';
$verifyCsrfContent = <<<'EOD'
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/*/callback',
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // Ensure session is started
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        // Generate token if it doesn't exist
        if (!$request->session()->has('_token')) {
            $request->session()->regenerateToken();
        }
        
        return parent::handle($request, $next);
    }
}
EOD;

file_put_contents($verifyCsrfPath, $verifyCsrfContent);
echo "✅ Updated VerifyCsrfToken middleware with fixes.\n";

// Fix 3: If using Redis, suggest key configuration fixes
if (config('session.driver') === 'redis') {
    echo "\nRecommended Redis Configuration for .env file:\n";
    echo "REDIS_HOST=127.0.0.1\n";
    echo "REDIS_PASSWORD=null\n";
    echo "REDIS_PORT=6379\n";
    echo "REDIS_CLIENT=predis\n";
    echo "CACHE_DRIVER=redis\n";
    echo "SESSION_DRIVER=redis\n";
    echo "SESSION_LIFETIME=120\n";
    echo "QUEUE_CONNECTION=redis\n";
}

// Fix 4: Update app config to show errors
$appDebug = env('APP_DEBUG');
if (!$appDebug) {
    echo "\nRecommend setting APP_DEBUG=true in .env for development to see error details.\n";
}

echo "\nDiagnostic complete and fixes applied. Please:\n";
echo "1. Run 'php artisan config:clear'\n";
echo "2. Run 'php artisan route:clear'\n";
echo "3. Run 'php artisan view:clear'\n";
echo "4. Run 'php artisan cache:clear'\n";
echo "5. Restart your web server\n";
echo "6. Try logging in again\n";

echo "\nIf you are still experiencing issues, consider adding this JavaScript to your login and register pages:\n";
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Directly add CSRF token to all forms
        document.querySelectorAll('form').forEach(function(form) {
            // Check if form already has a token
            if (!form.querySelector('input[name=\"_token\"]')) {
                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                console.log('Added CSRF token to form');
            }
        });
    });
</script>\n";

echo "\nYou might also try temporarily switching your session driver to 'file' in .env:\n";
echo "SESSION_DRIVER=file\n";
echo "\nGood luck!\n";