<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ripartizioni_estero', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('candidature_estero_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ripartizione_id')->references('id')->on('ripartizioni_estero');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->boolean('eletto');
        });

        Schema::create('candidature_estero_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ripartizione_id')->references('id')->on('ripartizioni_estero');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->boolean('eletto');
        });

        Schema::create('nazioni_estero', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ripartizione_id')->references('id')->on('ripartizioni_estero');
            $table->string('nome');
        });

        Schema::create('preferenze_estero_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidatura_id')->references('id')->on('candidature_estero_camera');
            $table->unsignedBigInteger('nazione_id')->references('id')->on('nazioni_estero');
            $table->unsignedInteger('preferenze');
        });

        Schema::create('preferenze_estero_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidatura_id')->references('id')->on('candidature_estero_camera');
            $table->unsignedBigInteger('nazione_id')->references('id')->on('nazioni_estero');
            $table->unsignedInteger('preferenze');
        });

        Schema::create('voti_liste_estero_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedBigInteger('nazione_id')->references('id')->on('nazioni_estero');
            $table->unsignedInteger('voti');
        });

        Schema::create('voti_liste_estero_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedBigInteger('nazione_id')->references('id')->on('nazioni_estero');
            $table->unsignedInteger('voti');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ripartizioni_estero');
        Schema::dropIfExists('candidature_estero_camera');
        Schema::dropIfExists('candidature_estero_senato');
        Schema::dropIfExists('nazioni_estero');
        Schema::dropIfExists('preferenze_estero_camera');
        Schema::dropIfExists('preferenze_estero_senato');
        Schema::dropIfExists('voti_liste_estero_camera');
        Schema::dropIfExists('voti_liste_estero_senato');
    }
};
