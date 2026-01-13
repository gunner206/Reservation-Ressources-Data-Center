<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // 👇 AJOUT IMPORTANT

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --------------------------------------------
        // 1. CRÉATION DES UTILISATEURS
        // --------------------------------------------
        
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Direction IT',
        ]);

        // Manager
        User::create([
            'name' => 'Responsable Technique',
            'email' => 'manager@datacenter.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'status' => 'active',
            'department' => 'Infrastructure',
        ]);

        // Interne
        User::create([
            'name' => 'Etudiant Test',
            'email' => 'etudiant@ecole.com',
            'password' => Hash::make('password123'),
            'role' => 'internal',
            'status' => 'active',
            'department' => 'Développement',
        ]);
        
        // Invité
        User::create([
            'name' => 'Visiteur Externe',
            'email' => 'guest@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'guest',
            'status' => 'pending',
            'department' => null,
        ]);
        
        // --------------------------------------------
        // 2. CRÉATION DES CATÉGORIES
        // --------------------------------------------
        $this->ajouterCategories();
    }
    
    /**
     * Méthode privée pour gérer les catégories
     */
    private function ajouterCategories(): void
    {
        // 👇 J'ai corrigé le nom de la table en minuscules : 'categories'
        // Vérifier d'abord si la table existe (évite les erreurs si tu n'as pas encore fait la migration categories)
        if (!\Illuminate\Support\Facades\Schema::hasTable('categories')) {
            $this->command->warn("⚠️ La table 'categories' n'existe pas encore. Crée la migration d'abord.");
            return;
        }

        $count = DB::table('categories')->count();
        
        if ($count > 0) {
            $this->command->info("ℹ️ La table categories contient déjà des données.");
            return;
        }
        
        $categories = [
            ['name' => 'Serveurs', 'icon' => '💻'],
            ['name' => 'Stockage', 'icon' => '🗄️'],
            ['name' => 'Réseau', 'icon' => '🌐'],
            ['name' => 'Sécurité', 'icon' => '🔒'],
            ['name' => 'Virtualisation', 'icon' => '☁️'],
        ];
        
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'icon' => $category['icon'], // Assure-toi que ta migration a bien une colonne 'icon'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ 5 catégories créées avec succès !');
    }
}