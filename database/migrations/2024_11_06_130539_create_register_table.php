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
        Schema::create('register', function (Blueprint $table) {
            $table->id()->index();  // Auto-incrementing primary key
            $table->string('user_unique_id')->unique()->index();
            $table->string('fullname');
            $table->string('email')->unique()->nullable();
            $table->bigInteger('mobile');
            $table->string('kmc_id')->unique()->index();
            $table->string('working_place')->nullable();
            $table->string('departuring_place')->nullable();
            $table->date('departuring_date')->nullable();
            $table->time('departuring_time')->nullable();
            $table->date('arrival_date')->nullable();
            $table->time('arrival_time')->nullable();
            $table->string('accommodation_assistance')->nullable();
            $table->string('accommodationa_at')->nullable();
            $table->string('fees_type')->nullable();
            $table->bigInteger('fees_no')->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register');
    }
};