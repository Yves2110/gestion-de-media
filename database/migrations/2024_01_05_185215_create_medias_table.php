<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('source_id')->constrained();
            $table->foreignId('thematique_id')->constrained();
            $table->string('title');
            $table->longText('description');
            $table->string('auteur');
            $table->string('code_media');
            $table->boolean('statut')->default(1);
            $table->string('audio');
            $table->boolean('type'); // 0 represente un audio et 1 represente une video
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
};
