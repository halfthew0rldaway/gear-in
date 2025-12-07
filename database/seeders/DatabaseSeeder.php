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
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@gear-in.dev'],
            [
                'name' => 'Gear-In Admin',
                'role' => User::ROLE_ADMIN,
                'password' => bcrypt('password'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'admin2@gear-in.dev'],
            [
                'name' => 'Gear-In Admin 2',
                'role' => User::ROLE_ADMIN,
                'password' => bcrypt('password'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'customer@gear-in.dev'],
            [
                'name' => 'Gear-In Customer',
                'role' => User::ROLE_CUSTOMER,
                'password' => bcrypt('password'),
            ],
        );
    }
}
