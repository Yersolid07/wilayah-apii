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
        Schema::create('cities_code', function (Blueprint $table) {
            $table->string('code', 5)->primary(); // Menggunakan 'code' sebagai primary key
            $table->string('province_code', 2); // Foreign key ke provinces_code
            $table->string('name');
            $table->timestamps();
        
            $table->foreign('province_code')->references('code')->on('provinces_code')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
