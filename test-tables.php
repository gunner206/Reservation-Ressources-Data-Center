<?php
// test-tables.php - Version corrigée
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2> TEST DES TABLES EN MAJUSCULES</h2>";

// 1. Vérification des tables - VERSION CORRIGÉE
echo "<h3> 1. Tables existantes :</h3>";
$tables = ['CATEGORIES', 'RESOURCES', 'INCIDENTS'];
foreach($tables as $table) {
    try {
        // Méthode 1 : Utilise SHOW TABLES LIKE 'CATEGORIES'
        $result = DB::select("SHOW TABLES LIKE '$table'");
        
        // Méthode alternative : Vérifie directement dans information_schema
        // $result = DB::select("SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?", [$table]);
        
        if(!empty($result)) {
            echo " <strong>$table</strong> existe<br>";
        } else {
            echo " <strong>$table</strong> MANQUANTE<br>";
        }
    } catch(Exception $e) {
        echo " <strong>$table</strong> : Erreur - " . $e->getMessage() . "<br>";
    }
}

// 2. Test création données - SIMPLIFIÉ
echo "<h3> 2. Test création données :</h3>";

try {
    // Vérifie d'abord si les modèles existent
    if(!class_exists('App\Models\Category')) {
        echo " Class App\Models\Category non trouvée<br>";
    } else {
        // Catégorie
        $cat = App\Models\Category::firstOrCreate(
            ['name' => 'Serveurs Test'],
            ['icon' => 'server']
        );
        echo " <strong>CATEGORIES</strong> : Catégorie créée (ID: {$cat->id})<br>";
    }
    
    if(!class_exists('App\Models\Resource')) {
        echo " Class App\Models\Resource non trouvée<br>";
    } else {
        // Ressource
        $res = App\Models\Resource::firstOrCreate(
            ['code' => 'TEST001'],
            [
                'name' => 'Serveur Principal',
                'category_id' => $cat->id ?? 1,
                'is_active' => true,
                'specs' => json_encode(['cpu' => 'Intel Xeon', 'ram' => '64GB'])
            ]
        );
        echo " <strong>RESOURCES</strong> : Ressource créée (ID: {$res->id})<br>";
        
        // Test relation si possible
        if(isset($res->category)) {
            echo " <strong>RELATION</strong> : Resource → Category : " . $res->category->name . "<br>";
        }
    }
    
} catch(Exception $e) {
    echo " <strong>ERREUR</strong> : " . $e->getMessage() . "<br>";
}

// 3. Affichage données simple
echo "<h3> 3. Données actuelles :</h3>";
try {
    $catCount = DB::table('CATEGORIES')->count();
    echo "<strong>CATEGORIES</strong> : $catCount catégories<br>";
    
    $resCount = DB::table('RESOURCES')->count();
    echo "<strong>RESOURCES</strong> : $resCount ressources<br>";
} catch(Exception $e) {
    echo "Erreur comptage : " . $e->getMessage() . "<br>";
}

// 4. Liens de test
echo "<h3> 4. Test interface :</h3>";
echo '<a href="http://localhost/Reservation-Ressources-Data-Center/public/ressources" target="_blank" style="color:blue;text-decoration:none;padding:10px;background:#f0f0f0;border-radius:5px;">📄 Accéder à /ressources</a><br><br>';
echo '<a href="http://localhost/Reservation-Ressources-Data-Center/public/ressources/create" target="_blank" style="color:green;text-decoration:none;padding:10px;background:#f0f0f0;border-radius:5px;">➕ Formulaire de création</a>';

echo "<h3 style='color:green;'>🎯 VOTRE MODULE EST PRÊT À ÊTRE TESTÉ !</h3>";