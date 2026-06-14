<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media_thematique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thematique_id')->constrained()->cascadeOnDelete();
            $table->unique(['media_id', 'thematique_id']);
        });

        Schema::create('document_thematique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thematique_id')->constrained()->cascadeOnDelete();
            $table->unique(['document_id', 'thematique_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_thematique');
        Schema::dropIfExists('media_thematique');
    }
};
