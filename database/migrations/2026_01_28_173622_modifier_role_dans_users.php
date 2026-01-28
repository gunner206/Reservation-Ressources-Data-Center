<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Important pour la commande SQL

return new class extends Migration
{
    public function up(): void
    {
        // On force la colonne à devenir un VARCHAR(50) simple
        // Cela permet d'écrire "technicien", "admin", "super-admin", etc.
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) DEFAULT 'user'");
    }

    public function down(): void
    {
        // (Optionnel) Si on veut revenir en arrière
        // DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user') DEFAULT 'user'");
    }
};