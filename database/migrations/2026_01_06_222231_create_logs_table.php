<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            
            // Relation avec l'utilisateur (nullable pour actions système)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete()
                  ->comment('Utilisateur ayant effectué l\'action (NULL = système)');
            
            // Action effectuée
            $table->string('action', 100)
                  ->comment('Type d\'action: create, update, delete, login, logout, etc.');
            
            // Cible de l'action
            $table->string('target_table', 50)
                  ->nullable()
                  ->comment('Table concernée par l\'action');
            
            $table->unsignedBigInteger('target_id')
                  ->nullable()
                  ->comment('ID de l\'enregistrement concerné');
            
            // Détails en JSON
            $table->json('details')
                  ->nullable()
                  ->comment('Détails de l\'action en JSON');
            
            // Informations système
            $table->string('ip_address', 45)
                  ->nullable()
                  ->comment('Adresse IP de l\'utilisateur');
            
            // Timestamp unique (pas de updated_at)
            $table->timestamp('created_at')
                  ->useCurrent()
                  ->comment('Date et heure de l\'action');
            
            // Index pour performances
            $table->index('user_id', 'idx_logs_user');
            $table->index('action', 'idx_logs_action');
            $table->index('created_at', 'idx_logs_created');
            $table->index(['target_table', 'target_id'], 'idx_logs_target');
            $table->index('ip_address', 'idx_logs_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};