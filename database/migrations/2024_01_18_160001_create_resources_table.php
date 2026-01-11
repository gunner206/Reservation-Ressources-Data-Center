<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->json('specs')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('code');
            $table->index('is_active');
        });
    }
    public function down() { Schema::dropIfExists('resources'); }
};