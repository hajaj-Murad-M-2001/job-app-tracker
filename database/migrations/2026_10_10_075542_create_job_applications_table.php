<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('company_name');
    $table->string('position');
    $table->string('status')->default('wishlist');
    $table->string('job_url')->nullable();
    $table->unsignedInteger('salary')->nullable();
    $table->text('notes')->nullable();
    $table->date('applied_at')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
