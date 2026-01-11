<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low','medium','high','critical'])->default('medium');
            $table->enum('status', ['open','in_progress','resolved','closed'])->default('open');
            $table->timestamps();
            $table->index('priority');
            $table->index('status');
        });
    }
    public function down() { Schema::dropIfExists('incidents'); }
};