<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create-super-admin 
                           {--email= : Admin email address}
                           {--name= : Admin name}
                           {--password= : Admin password}';

    /**
     * The console command description.
     */
    protected $description = 'Create a super admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating Super Admin User...');

        // Get input
        $email = $this->option('email') ?: $this->ask('Admin Email');
        $name = $this->option('name') ?: $this->ask('Admin Name');
        $password = $this->option('password') ?: $this->secret('Admin Password');

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|min:2',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        // Create user
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
                'admin_role' => 'super_admin',
                'admin_since' => now(),
                'account_status' => 'active'
            ]);

            $this->info("Super Admin created successfully!");
            $this->info("Email: {$user->email}");
            $this->info("Name: {$user->name}");
            $this->info("Admin Role: {$user->admin_role}");
            
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create super admin: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}