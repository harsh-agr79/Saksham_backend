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
        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('coding_challenges')->onDelete('cascade');
            $table->text('code'); // Stores submitted code
            $table->enum('status', ['pending', 'correct', 'incorrect'])->default('pending');
            $table->float('execution_time')->nullable(); // Stores execution time in seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_submissions');
    }
};
