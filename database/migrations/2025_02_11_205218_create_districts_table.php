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
        Schema::create('districts_code', function (Blueprint $table) {
            $table->string('code', 8)->primary(); // Menggunakan 'code' sebagai primary key
            $table->string('city_code', 5); // Foreign key ke cities_code
            $table->string('name');
            $table->timestamps();
        
            $table->foreign('city_code')->references('code')->on('cities_code')->onDelete('cascade');
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
