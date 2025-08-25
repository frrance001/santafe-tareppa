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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ride_id')->constrained('rides')->onDelete('cascade');
        $table->foreignId('passenger_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
        $table->decimal('amount', 8, 2);
        $table->string('method')->default('GCash');
        $table->string('status')->default('success');
        $table->timestamps();
    });
}

};