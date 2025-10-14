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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('phone')->unique();
            $table->integer('age');
            $table->string('sex');

            // File uploads
            $table->string('photo')->nullable();
            $table->string('business_permit')->nullable();
            $table->string('barangay_clearance')->nullable();
            $table->string('police_clearance')->nullable();

            // System fields
            $table->rememberToken();
            $table->timestamps();

            // Extra (availability & verification)
            $table->boolean('availability')->default(false);
            $table->boolean('is_available')->default(false);
            $table->string('verification_code')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
