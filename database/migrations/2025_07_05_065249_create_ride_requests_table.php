<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ride_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('passenger_id')->constrained('users');
        $table->foreignId('driver_id')->constrained('users');
        $table->string('pickup_location');
        $table->string('dropoff_location');
        $table->string('phone_number');
        $table->string('status')->default('pending'); // pending, accepted, completed, etc.
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
