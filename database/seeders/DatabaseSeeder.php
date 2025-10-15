<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Babacar Ka',
            'email' => 'admin@ndindy.com',
            'password' => Hash::make('test123'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'manager Ka',
            'email' => 'manager@ndindy.com',
            'password' => Hash::make('passer123'),
            'role' => 'manager',
        ]);
        User::factory()->create([
            'name' => 'staff Ka',
            'email' => 'staff@ndindy.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // User::factory(3)->create();


        $this->call([
            CategorySeeder::class,
            CountrySeeder::class,
            ProductSeeder::class,
            ArrivalsSeeder::class,
            ItemsSeeder::class,
        ]);

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('📧 Email Admin: babs@ndindy.com');
        $this->command->info('🔑 Mot de passe: test123');
    }
}
