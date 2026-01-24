<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
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
        $admin = User::create([
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

        $student = User::create([
            'name' => 'Etudiant',
            'email' => 'etudiant@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'internal',
            'status' => 'active',
            'bio' => 'Etudiant FSTT',
            'department' => null
        ]);

        
        // --------------------------------------------
        // 2. CRÉATION DES CATÉGORIES
        // --------------------------------------------
        $this->ajouterCategories();

        // --------------------------------------------
        // 3. CRÉATION DES RESSOURCES
        // --------------------------------------------
        $this->ajouterRessources();

        $this->ajouterReservations($admin, $student);
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
                'description' => 'Serveur rack haute performance idéal pour la virtualisation et les bases de données.'            ],
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
    }

    private function ajouterReservations($admin, $student): void
    {
        if (!Schema::hasTable('reservations')) return;
        if (DB::table('reservations')->count() > 0) return;

        $serveur = Resource::where('name', 'Dell PowerEdge R740')->first();
        $stock = Resource::where('name', 'Baie NetApp AFF A400')->first();

        if (!$serveur || !$stock) return;

        $reservations = [
            // CAS 1 : Réservation EN COURS (Pour tester l'état "Occupé")
            // De 8h ce matin à 18h ce soir
            [
                'user_id' => $admin->id,
                'resource_id' => $serveur->id,
                'start_date' => now()->setHour(8)->setMinute(0),
                'end_date' => now()->setHour(18)->setMinute(0),
                'status' => 'approved', // Déjà validé
                'type' => 'maintenance',
                'justification' => 'Maintenance mensuelle planifiée',
                'validated_by' => $admin->id
            ],

        // CAS 2 : Réservation EN ATTENTE (Pour tester la validation Manager)
        // Pour demain
            [
                'user_id' => $student->id,
                'resource_id' => $stock->id,
                'start_date' => now()->addDay()->setHour(10)->setMinute(0),
                'end_date' => now()->addDay()->setHour(12)->setMinute(0),
                'status' => 'pending', // En attente
                'type' => 'standard',
                'validated_by' => null,
                'justification' => 'Besoin pour le projet de fin d\'année',
            ],

        // CAS 3 : Réservation FUTURE VALIDÉE (Pour le planning)
        // Après-demain
            [
                'user_id' => $student->id,
                'resource_id' => $serveur->id,
                'start_date' => now()->addDays(2)->setHour(14)->setMinute(0),
                'end_date' => now()->addDays(2)->setHour(16)->setMinute(0),
                'status' => 'approved',
                'type' => 'standard',
                'justification' => 'TP Intelligence Artificielle',
                'validated_by' => $admin->id
            ]
        ];

        foreach ($reservations as $res) {
            DB::table('reservations')->insert([
                'user_id' => $res['user_id'],
                'resource_id' => $res['resource_id'],
                'start_date' => $res['start_date'],
                'end_date' => $res['end_date'],
                'status' => $res['status'],
                'type' => $res['type'],
                'justification' => $res['justification'],
                'validated_by' => $res['validated_by'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}