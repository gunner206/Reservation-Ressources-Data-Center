<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. COMPTE ADMIN (C'est toi)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('password123'), // Mot de passe crypté
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Direction IT',
        ]);

        // 2. COMPTE MANAGER (Pour l'étudiant B)
        User::create([
            'name' => 'Responsable Technique',
            'email' => 'manager@datacenter.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'status' => 'active',
            'department' => 'Infrastructure',
        ]);

        // 3. COMPTE UTILISATEUR INTERNE (Pour l'étudiant C)
        User::create([
            'name' => 'Etudiant Test',
            'email' => 'etudiant@ecole.com',
            'password' => Hash::make('password123'),
            'role' => 'internal', // Attention : j'ai mis 'internal' comme dans ta migration
            'status' => 'active',
            'department' => 'Développement',
        ]);
        
        // 4. COMPTE INVITÉ (Pour tester les restrictions)
        User::create([
            'name' => 'Visiteur Externe',
            'email' => 'guest@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'guest',
            'status' => 'pending', // Compte en attente de validation
            'department' => null,
        ]);
    }
}