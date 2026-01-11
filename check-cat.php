<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h2>Vérification des catégories</h2>";

$categories = DB::table('CATEGORIES')->get();

if ($categories->isEmpty()) {
    echo "❌ Aucune catégorie trouvée";
} else {
    echo "✅ " . $categories->count() . " catégorie(s) trouvée(s) :<br><br>";
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Icon</th></tr>";
    
    foreach($categories as $cat) {
        echo "<tr>";
        echo "<td>{$cat->id}</td>";
        echo "<td>{$cat->name}</td>";
        echo "<td style='font-size:20px;'>{$cat->icon}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo '<br><br>';
echo '<a href="/Reservation-Ressources-Data-Center/public/ressources/create" style="color:white;background:blue;padding:10px 20px;text-decoration:none;">📝 TESTER LE FORMULAIRE</a>';