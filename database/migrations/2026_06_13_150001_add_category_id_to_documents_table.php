<?php

use App\Models\Category;
use App\Models\Document;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('publication_date')->constrained()->nullOnDelete();
        });

        foreach (Document::query()->distinct()->pluck('categorie')->filter() as $label) {
            $category = Category::firstOrCreate(['label' => $label]);
            Document::where('categorie', $label)->update(['category_id' => $category->id]);
        }
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
