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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('author_id');
            $table->string('marking');
            $table->date('read_at')->nullable();
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->timestamps();


            $table->foreign('author_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
