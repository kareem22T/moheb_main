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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('facebook', 150)->nullable();
            $table->string('instagram', 150)->nullable();
            $table->string('tiktok', 150)->nullable();
            $table->string('youtube', 150)->nullable();
            $table->string('x', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
