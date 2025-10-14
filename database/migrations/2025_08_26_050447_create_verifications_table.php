<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('verifications', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('business_permit_path');
$table->string('police_clearance_path');
$table->string('barangay_clearance_path');
$table->enum('status', ['pending','approved','rejected'])->default('pending');
$table->text('notes')->nullable();
$table->timestamps();
});
}


public function down(): void
{
Schema::dropIfExists('verifications');
}
};