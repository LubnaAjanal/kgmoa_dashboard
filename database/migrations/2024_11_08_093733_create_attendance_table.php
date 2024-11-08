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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('registered_id')->index();
            $table->string('user_unique_id')->index(); // Assuming this is the ID column from the register table
            $table->timestamp('scanned_at')->useCurrent(); // Store the scan timestamp
            $table->bigInteger('count_attendance')->index();
            $table->softDeletes(); // Soft delete support
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraint
            $table->foreign('user_unique_id')
                  ->references('user_unique_id')
                  ->on('register')
                  ->onDelete('cascade'); 

                  $table->foreign('registered_id')
                  ->references('id')
                  ->on('register')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
