<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        $this->renderable(function (InsufficientFundsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        });
        
        $this->renderable(function (UnauthorizedTripAccessException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 403);
            }
            
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        });
    }
}