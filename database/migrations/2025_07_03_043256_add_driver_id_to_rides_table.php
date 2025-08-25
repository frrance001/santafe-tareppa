<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverIdToRidesTable extends Migration
{
    public function up()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->foreignId('driver_id')
                ->nullable()
                ->after('user_id') // Optional: places driver_id after user_id
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
}
