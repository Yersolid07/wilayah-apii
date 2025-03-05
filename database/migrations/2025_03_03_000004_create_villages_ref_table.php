<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('villages_ref', function (Blueprint $table) {
            $table->string('village_id')->primary();
            $table->string('province_id');
            $table->string('regency_id');
            $table->string('district_id');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
            
            $table->foreign('province_id')->references('province_id')->on('provinces_ref');
            $table->foreign('regency_id')->references('regency_id')->on('regencies_ref');
            $table->foreign('district_id')->references('district_id')->on('districts_ref');
        });
    }

    public function down()
    {
        Schema::dropIfExists('villages_ref');
    }
}; 