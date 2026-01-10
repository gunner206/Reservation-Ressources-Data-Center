<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();  // Colonne 'id' automatique
            $table->string('nom');  // Nom de la catégorie
            $table->text('description')->nullable();  // Description (peut être vide)
            $table->timestamps();  // Dates de création et modification automatiques
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};