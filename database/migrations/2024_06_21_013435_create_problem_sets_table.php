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
        Schema::create('problem_sets', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('subject_code_id')->nullable();
            $table->enum('term',['prelim','mid-term','pre-final','final']);
            $table->timestamps();


            $table->foreign('subject_code_id')->references('id')->on('subject_codes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_sets');
    }
};
