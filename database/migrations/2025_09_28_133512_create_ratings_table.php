<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade'); // who gave the rating
            // polymorphic target (so we can rate passengers or drivers)
            $table->unsignedBigInteger('rateable_id');
            $table->string('rateable_type');
            $table->tinyInteger('score'); // 1 - 5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['rateable_id','rateable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
