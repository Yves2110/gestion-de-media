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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('source_id')->constrained();
            $table->string('thematique_id');
            $table->string('title');
            $table->string('auteur');
            $table->string('code_document')->nullable();
            $table->integer('page');
            $table->string('edition');
            $table->date('publication_date');
            $table->string('categorie');
            $table->string('picture');
            $table->string('file_doc');
            $table->longText('resume')->nullable();
            $table->boolean('statut_publication')->default(0);
            $table->boolean('ask_form')->default(0);
            $table->longText('localisation')->nullable();
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
        Schema::dropIfExists('documents');
    }
};
