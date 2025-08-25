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
    Schema::create('complaints', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->text('message');
        $table->enum('status', ['pending', 'resolved'])->default('pending');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}
};
