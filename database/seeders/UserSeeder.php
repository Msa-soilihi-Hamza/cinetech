<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur normal
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@cinetech.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
        ]);

        // Créer quelques utilisateurs supplémentaires
        User::factory(5)->create();

        $this->command->info('Utilisateurs créés avec succès !');
        $this->command->info('Utilisateur test:');
        $this->command->info('Email: test@cinetech.com');
        $this->command->info('Mot de passe: test123');
    }
}
