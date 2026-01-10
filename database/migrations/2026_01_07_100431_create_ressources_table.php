<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ressources', function (Blueprint $table) {
            // IDENTIFICATION
            $table->id();
            $table->string('nom');  // Nom de la ressource
            $table->text('description')->nullable();
            
            // LIEN avec la catégorie
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            // "chaque ressource appartient à une catégorie"
            
            // CARACTÉRISTIQUES TECHNIQUES
            $table->string('cpu')->nullable();  // Processeur
            $table->string('ram')->nullable();  // Mémoire
            $table->string('stockage')->nullable();  // Espace disque
            $table->string('os')->nullable();  // Système d'exploitation
            
            // ÉTAT DE LA RESSOURCE
            $table->enum('statut', ['actif', 'maintenance', 'hors_service'])->default('actif');
            
            // LIEN avec le responsable (depuis table users)
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            // "chaque ressource a un responsable technique"
            
            $table->timestamps();  // Dates automatiques
        });
    }

    public function down()
    {
        Schema::dropIfExists('ressources');
    }
};