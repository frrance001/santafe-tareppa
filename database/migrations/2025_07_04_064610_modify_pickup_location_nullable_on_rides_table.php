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
    Schema::table('rides', function (Blueprint $table) {
        $table->string('pickup_location')->nullable()->change(); // Make the pickup_location nullable
    });
}

public function down()
{
    Schema::table('rides', function (Blueprint $table) {
        $table->string('pickup_location')->nullable(false)->change(); // Revert to non-nullable
    });
}
};