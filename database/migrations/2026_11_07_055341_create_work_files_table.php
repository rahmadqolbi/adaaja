<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('work_files', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('original_name');
        $table->string('stored_name');
        $table->string('path');
        $table->string('mime_type');
        $table->unsignedBigInteger('size');
        $table->text('description')->nullable();
        $table->string('source')->default('whatsapp');
        $table->date('upload_date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_files');
    }
};
