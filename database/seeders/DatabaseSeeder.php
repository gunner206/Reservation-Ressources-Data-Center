<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer un utilisateur test (déjà présent)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // 2. AJOUTER LES CATÉGORIES
        $this->ajouterCategories();
    }
    
    /**
     * Méthode pour ajouter les catégories
     */
    private function ajouterCategories(): void
    {
        // Vérifier si la table CATEGORIES existe déjà avec des données
        $count = DB::table('CATEGORIES')->count();
        
        if ($count > 0) {
            $this->command->info("ℹ️  La table CATEGORIES contient déjà $count catégorie(s)");
            return;
        }
        
        // Catégories pour le data center
        $categories = [
            ['name' => 'Serveurs', 'icon' => '💻'],
            ['name' => 'Stockage', 'icon' => '🗄️'],
            ['name' => 'Réseau', 'icon' => '🌐'],
            ['name' => 'Sécurité', 'icon' => '🔒'],
            ['name' => 'Virtualisation', 'icon' => '☁️'],
        ];
        
        foreach ($categories as $category) {
            DB::table('CATEGORIES')->insert([
                'name' => $category['name'],
                'icon' => $category['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info(' 5 catégories créées avec succès !');
    }
}