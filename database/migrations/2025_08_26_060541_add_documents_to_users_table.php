<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_permit')->nullable()->after('role');
            $table->string('barangay_clearance')->nullable()->after('business_permit');
            $table->string('police_clearance')->nullable()->after('barangay_clearance');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['business_permit', 'barangay_clearance', 'police_clearance']);
        });
    }
};
