<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('titre');  // Titre du problème
            $table->text('description');  // Détails du problème
            
            // LIEN avec la ressource concernée
            $table->foreignId('ressource_id')->constrained()->onDelete('cascade');
            // "chaque incident concerne une ressource"
            
            // LIEN avec l'utilisateur qui signale
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            // "chaque incident est signalé par un utilisateur"
            
            // STATUT de l'incident
            $table->enum('statut', ['ouvert', 'en_cours', 'resolu', 'ferme'])->default('ouvert');
            
            // NIVEAU de gravité
            $table->enum('priorite', ['basse', 'moyenne', 'haute', 'critique'])->default('moyenne');
            
            // DATES importantes
            $table->timestamp('date_resolution')->nullable();  // Quand c'est résolu
            $table->text('solution')->nullable();  // Comment c'était résolu
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};