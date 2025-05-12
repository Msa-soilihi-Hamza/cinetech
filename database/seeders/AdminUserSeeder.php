<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cinetech.com',
            'password' => Hash::make('admin123'), // Remplacez par un mot de passe plus sécurisé
            'role' => 'admin',
        ]);

        $this->command->info('Utilisateur admin créé avec succès !');
        $this->command->info('Email: admin@cinetech.com');
        $this->command->info('Mot de passe: admin123');
    }
}
