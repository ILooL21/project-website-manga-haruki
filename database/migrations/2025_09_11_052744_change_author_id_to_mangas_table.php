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
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropForeign('mangas_author_id_foreign');
            $table->dropIndex('mangas_author_id_foreign');
            $table->dropColumn('author_id');

            $table->string('author_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn('author_name');

            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
        });
    }
};
