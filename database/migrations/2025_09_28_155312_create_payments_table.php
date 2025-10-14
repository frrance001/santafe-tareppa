<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('ride_id')->index();
        $table->string('method');
        $table->integer('amount'); // stored in centavos (₱100 = 10000)
        $table->string('status')->default('pending');
        $table->string('gateway_id')->nullable();
        $table->timestamps();
    });
}
};
