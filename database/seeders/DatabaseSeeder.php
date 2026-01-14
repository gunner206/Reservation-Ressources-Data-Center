<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // --------------------------------------------
        // 3. CRÉATION DES RESSOURCES (AVEC DESCRIPTIONS)
        // --------------------------------------------
        $this->ajouterRessources();
    }
    
    /**
     * Méthode pour gérer les catégories (Code de Yassine)
     */
    private function ajouterCategories(): void
    {
        if (!Schema::hasTable('categories')) {
            $this->command->warn("⚠️ La table 'categories' n'existe pas encore.");
            return;
        }

        if (DB::table('categories')->count() > 0) {
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
                'icon' => $category['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ 5 catégories créées avec succès !');
    }

    /**
     * Méthode pour ajouter les ressources avec descriptions détaillées
     */
    private function ajouterRessources(): void
    {
        if (!Schema::hasTable('resources')) return;

        if (DB::table('resources')->count() > 0) {
            $this->command->info("ℹ️ La table resources contient déjà des données.");
            return;
        }

        $resources = [
            [
                'name' => 'Dell PowerEdge R740',
                'code' => 'SRV-DELL-01',
                'category_id' => 1,
                'description' => 'Serveur rack haute performance idéal pour la virtualisation et les bases de données.'
            ],
            [
                'name' => 'Baie NetApp AFF A400',
                'code' => 'STO-NET-01',
                'category_id' => 2,
                'description' => 'Système de stockage All-Flash ultra-rapide pour une gestion efficace des données.'
            ],
            [
                'name' => 'Cisco Catalyst 9300',
                'code' => 'SW-CIS-01',
                'category_id' => 3,
                'description' => 'Switch réseau intelligent 48 ports avec support PoE+ pour une infrastructure moderne.'
            ],
            [
                'name' => 'Firewall FortiGate 100F',
                'code' => 'FW-FORT-01',
                'category_id' => 4,
                'description' => 'Sécurité périmétrique avancée avec inspection SSL et protection contre les menaces.'
            ],
            [
                'name' => 'Cluster VMware ESXi',
                'code' => 'VIRT-VMW-01',
                'category_id' => 5,
                'description' => 'Environnement cloud privé permettant le déploiement flexible de machines virtuelles.'
            ],
        ];

        foreach ($resources as $res) {
            DB::table('resources')->insert([
                'name' => $res['name'],
                'code' => $res['code'],
                'category_id' => $res['category_id'],
                'description' => $res['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ 5 ressources avec descriptions ajoutées avec succès !');
    }
}