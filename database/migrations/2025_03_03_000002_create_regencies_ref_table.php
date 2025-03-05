<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regencies_ref', function (Blueprint $table) {
            $table->string('regency_id')->primary();
            $table->string('province_id');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
            
            $table->foreign('province_id')->references('province_id')->on('provinces_ref');
        });
    }

    public function down()
    {
        Schema::dropIfExists('regencies_ref');
    }
}; 