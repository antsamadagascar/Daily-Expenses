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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#3B82F6'); // Code couleur hex
            $table->string('icon')->nullable(); // Nom de l'icône
            $table->boolean('is_default')->default(false); // Catégorie système
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null = catégorie système
            $table->timestamps();
            
            $table->unique(['name', 'user_id']); // Éviter les doublons par utilisateur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
