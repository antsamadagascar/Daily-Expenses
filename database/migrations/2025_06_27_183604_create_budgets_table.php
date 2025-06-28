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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 10, 2); // Montant budgété
            $table->string('name'); // Nom du budget
            $table->text('description')->nullable(); // Description du budget
            $table->date('start_date'); // Date de début
            $table->date('end_date'); // Date de fin
            $table->enum('period', ['monthly', 'weekly', 'yearly', 'custom'])->default('monthly');
            $table->boolean('is_active')->default(true); // Budget actif ou non
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['user_id', 'category_id']);
            $table->index(['user_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
