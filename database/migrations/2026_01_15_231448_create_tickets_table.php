<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Qui a signalé ?
        $table->string('subject'); // Le titre (ex: Panne Clim)
        $table->text('message');   // Les détails
        $table->enum('priority', ['low', 'medium', 'high'])->default('low'); // Urgence
        $table->enum('status', ['open', 'resolved'])->default('open'); // État du ticket
        $table->timestamps();
    });
}
};
