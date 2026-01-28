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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Relation avec l'utilisateur
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Utilisateur qui reçoit la notification');
            
            // Type de notification
            $table->string('type', 100)
                  ->comment('Type: reservation_approved, incident_resolved, etc.');
            
            // Données en JSON
            $table->json('data')
                  ->comment('Message de notification');
            
            // Date de lecture
            $table->timestamp('read_at')
                  ->nullable()
                  ->comment('NULL = non lue, sinon date de lecture');
            
            $table->timestamps();
            
            // Index pour performances
            $table->index('user_id', 'idx_notifications_user');
            $table->index('type', 'idx_notifications_type');
            $table->index('read_at', 'idx_notifications_read');
            $table->index('created_at', 'idx_notifications_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};