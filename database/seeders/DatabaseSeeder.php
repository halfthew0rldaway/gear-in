<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users FIRST before other seeders that might need them
        $this->command->info('Creating default users...');
        
        try {
            User::updateOrCreate(
                ['email' => 'admin@gear-in.dev'],
                [
                    'name' => 'Gear-In Admin',
                    'role' => User::ROLE_ADMIN,
                    'password' => bcrypt('password'),
                ],
            );
            $this->command->info('✅ Admin user created: admin@gear-in.dev');

            User::updateOrCreate(
                ['email' => 'admin2@gear-in.dev'],
                [
                    'name' => 'Gear-In Admin 2',
                    'role' => User::ROLE_ADMIN,
                    'password' => bcrypt('password'),
                ],
            );
            $this->command->info('✅ Admin 2 user created: admin2@gear-in.dev');

            User::updateOrCreate(
                ['email' => 'customer@gear-in.dev'],
                [
                    'name' => 'Gear-In Customer',
                    'role' => User::ROLE_CUSTOMER,
                    'password' => bcrypt('password'),
                ],
            );
            $this->command->info('✅ Customer user created: customer@gear-in.dev');
        } catch (\Exception $e) {
            $this->command->error('Error creating users: ' . $e->getMessage());
            throw $e;
        }

        // Run other seeders in order
        $this->command->info('Running seeders...');
        
        try {
            $this->call([
                CategorySeeder::class,
                ProductSeeder::class,
                OrderSeeder::class,
                ReviewSeeder::class,
            ]);
            
            $this->command->info('✅ All seeders completed successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error running seeders: ' . $e->getMessage());
            throw $e;
        }
    }
}
