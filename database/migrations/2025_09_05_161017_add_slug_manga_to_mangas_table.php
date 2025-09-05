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
            $table->string('slug')->nullable()->after('title');
        });

        // Fill slug for all existing rows
        $mangaModel = new \App\Models\Manga;
        foreach ($mangaModel->all() as $manga) {
            // Use Str::slug(title) + id for uniqueness
            $slug = \Illuminate\Support\Str::slug($manga->title ?: 'manga') . '-' . $manga->id;
            $manga->slug = $slug;
            $manga->save();
        }

        // Now add unique index
        Schema::table('mangas', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
