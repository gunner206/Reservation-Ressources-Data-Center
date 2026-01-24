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
        // 1. CRÉATION DE L'ÉQUIPE TECHNIQUE (REAL DATA)
        // --------------------------------------------
        
        // 1. Chaimae (Admin)
        User::create([
            'name' => 'Chaimae',
            'email' => 'chaimae@centrum.ma', // Email vu dans ta capture
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Direction IT',
            'avatar' => 'images/chaimae.png', // Chemin vu dans ta DB
            'bio' => 'Administratrice Système & Développeuse. Passionnée par le Cloud Computing et le développement Web.',
            'linkedin_url' => 'https://www.linkedin.com/in/chaimae-zaki-8250992a3',
            'github_url' => 'https://github.com/zakichaimae-byte',
        ]);

        // 2. Alae (Manager / Technicien)
        User::create([
            'name' => 'Alae',
            'email' => 'alae@centrum.ma',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'status' => 'active',
            'department' => 'Infrastructure',
            'avatar' =>'images/alae.png', // Met le chemin si tu as l'image
            'bio' => 'Expert en administration système et réseaux. Je m\'assure que l\'infrastructure du Data Center est toujours opérationnelle et sécurisée.',
            'linkedin_url' => 'https://www.linkedin.com/in/alae-jaaouani-7a9b4a396',
            'github_url' => 'https://github.com/Alae-jaa',
        ]);

        // 3. Yassine (Manager / Technicien)
        User::create([
            'name' => 'Yassine',
            'email' => 'yassine@centrum.ma',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'status' => 'active',
            'department' => 'Développement',
            'avatar' => null,
            'bio' => 'Développeur Full Stack et passionné d\'automatisation. J\'aime optimiser le code pour garantir une fluidité maximale aux utilisateurs.',
            'github_url' => 'https://github.com/gunner206',
        ]);

        // 4. Houssam (Manager / Technicien)
        User::create([
            'name' => 'Houssam',
            'email' => 'houssam@centrum.ma',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'status' => 'active',
            'department' => 'Maintenance',
            'avatar' => null,
            'bio' => 'Spécialiste IT et maintenance hardware. Je veille à la performance des équipements et à la résolution rapide des incidents techniques.',
            'github_url' => 'https://github.com/houssam-icon',
        ]);

        // 5. Visiteur Test (Optionnel)
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
        // 3. CRÉATION DES RESSOURCES
        // --------------------------------------------
        $this->ajouterRessources();
    }
    
    /**
     * Méthode pour gérer les catégories
     */
    private function ajouterCategories(): void
    {
        if (!Schema::hasTable('categories')) return;
        if (DB::table('categories')->count() > 0) return;
        
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
    }

    /**
     * Méthode pour ajouter les ressources
     */
    private function ajouterRessources(): void
    {
        if (!Schema::hasTable('resources')) return;
        if (DB::table('resources')->count() > 0) return;

        $resources = [
            [
                'name' => 'Dell PowerEdge R740',
                'code' => 'SRV-DELL-01',
                'category_id' => 1,
                'description' => 'Serveur rack haute performance idéal pour la virtualisation.'
            ],
            [
                'name' => 'Baie NetApp AFF A400',
                'code' => 'STO-NET-01',
                'category_id' => 2,
                'description' => 'Système de stockage All-Flash ultra-rapide.'
            ],
            [
                'name' => 'Cisco Catalyst 9300',
                'code' => 'SW-CIS-01',
                'category_id' => 3,
                'description' => 'Switch réseau intelligent 48 ports.'
            ],
            [
                'name' => 'Firewall FortiGate 100F',
                'code' => 'FW-FORT-01',
                'category_id' => 4,
                'description' => 'Sécurité périmétrique avancée.'
            ],
            [
                'name' => 'Cluster VMware ESXi',
                'code' => 'VIRT-VMW-01',
                'category_id' => 5,
                'description' => 'Environnement cloud privé.'
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
    }
}