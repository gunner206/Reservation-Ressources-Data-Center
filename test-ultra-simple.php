<?php
// test-ultra-simple.php - Test sans complications
echo "<h1> TEST ULTRA SIMPLE</h1>";

// 1. Charge Laravel
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo " Laravel chargé<br><br>";

// 2. Test DB basique
try {
    $pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
    echo " Connexion DB OK<br>";
    echo " Base de données : " . $pdo->query('SELECT DATABASE()')->fetchColumn() . "<br><br>";
} catch(Exception $e) {
    echo " DB Error: " . $e->getMessage() . "<br><br>";
}

// 3. Liste toutes les tables
echo "<h3> Liste des tables :</h3>";
$allTables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach($allTables as $tableObj) {
    foreach($tableObj as $tableName) {
        echo "• $tableName<br>";
    }
}

// 4. Test direct avec DB::table()
echo "<h3> Test direct des tables :</h3>";
try {
    $catCount = Illuminate\Support\Facades\DB::table('CATEGORIES')->count();
    echo " CATEGORIES : $catCount ligne(s)<br>";
} catch(Exception $e) {
    echo " CATEGORIES : " . $e->getMessage() . "<br>";
}

try {
    $resCount = Illuminate\Support\Facades\DB::table('RESOURCES')->count();
    echo " RESOURCES : $resCount ligne(s)<br>";
} catch(Exception $e) {
    echo " RESOURCES : " . $e->getMessage() . "<br>";
}

// 5. Liens
echo "<h3> Testez votre interface :</h3>";
echo '<a href="/Reservation-Ressources-Data-Center/public/ressources" style="font-size:18px;color:white;background:green;padding:10px 20px;text-decoration:none;border-radius:5px;"> ACCÉDER AU MODULE RESSOURCES</a>';