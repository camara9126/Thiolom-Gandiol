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
        Schema::table('vente_items', function (Blueprint $table) {
            $table->decimal('taux_tva', 5, 2)->default(18);
            $table->decimal('montant_tva', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vente_items', function (Blueprint $table) {
            //
        });
    }
};
