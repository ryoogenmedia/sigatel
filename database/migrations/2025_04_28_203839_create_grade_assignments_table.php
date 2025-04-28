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
        Schema::create('grade_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id');
            $table->foreignId('teacher_id');
            $table->foreignId('school_subject_id');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->text('reason_teacher')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->string('documentation_image')->nullable();
            $table->string('file_assignment')->nullable();
            $table->dateTime('schedule_time')->nullable();
            $table->dateTime('finish_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_assignments');
    }
};
