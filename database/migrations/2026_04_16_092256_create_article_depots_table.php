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
        Schema::create('article_depots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('magasin_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_depots');
    }
};
