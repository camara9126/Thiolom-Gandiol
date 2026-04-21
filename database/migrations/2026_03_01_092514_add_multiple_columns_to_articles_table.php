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
        Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fournisseur_id')->nullable()->constrained()->cascadeOnDelete();
        $table->string('nom');
        $table->string('image');
        $table->text('description')->nullable();
        $table->string('code'); // unique par entreprise
        $table->decimal('prix_achat', 12, 2)->default(0);
        $table->decimal('prix_vente', 12, 2)->default(0);
        $table->integer('stock')->default(0);
        $table->integer('stock_min')->default(0);
        $table->boolean('statut')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
};
