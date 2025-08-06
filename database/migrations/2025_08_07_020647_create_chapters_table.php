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
       Schema::create('chapters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('manga_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->float('chapter_number');
        $table->date('release_date')->nullable();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang upload chapter
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
