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
        // 1. TABLE USERS (Ton code personnalisé)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Informations personnelles
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->string('cne')->nullable()->unique();
            $table->string('password');
            
            // Rôle et statut
            $table->enum('role', ['admin', 'manager', 'internal', 'guest'])
                  ->default('internal');
            $table->enum('status', ['active', 'pending', 'banned'])
                  ->default('active');
            
            // Informations complémentaires
            $table->string('department', 100)->nullable();
            
            // Laravel defaults
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Index
            $table->index('email');
            $table->index('role');
            $table->index('status');
        });

        // 2. TABLE PASSWORD_RESET_TOKENS (Remise par défaut)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. TABLE SESSIONS (Celle qui te manque !)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};