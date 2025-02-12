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
        Schema::create('subdistricts_code', function (Blueprint $table) {
            $table->string('code', 13)->primary(); // Menggunakan 'code' sebagai primary key
            $table->string('district_code', 8); // Foreign key ke districts_code
            $table->string('name');
            $table->timestamps();
        
            $table->foreign('district_code')->references('code')->on('districts_code')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
    }
};
