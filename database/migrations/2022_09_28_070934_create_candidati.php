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
        Schema::create('liste', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('coalizioni', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('candidati', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('anno_nascita', 4);
            $table->string('luogo_nascita');
            $table->string('altro_1');
            $table->string('altro_2');
            $table->string('sesso', 1);
        });

        Schema::create('candidature_collegi_plurinominali_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->nullable()->references('id')->on('liste');
            $table->unsignedBigInteger('collegio_plurinominale_camera_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidati_candidature_collegi_plurinominali_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedBigInteger('candidatura_collegio_plurinominale_camera_id')->references('id')->on('candidature_collegi_plurinominali_camera');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidature_collegi_plurinominali_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->nullable()->references('id')->on('liste');
            $table->unsignedBigInteger('collegio_plurinominale_senato_id')->references('id')->on('collegi_plurinominali_senato');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidati_candidature_collegi_plurinominali_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedBigInteger('candidatura_collegio_plurinominale_senato_id')->references('id')->on('candidature_collegi_plurinominali_senato');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidature_collegi_uninominale_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coalizione_id')->references('id')->on('coalizioni');
            $table->unsignedBigInteger('collegio_uninominale_camera_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidature_collegi_uninominale_camera_liste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->nullable()->references('id')->on('liste');
            $table->unsignedBigInteger('candidatura_collegio_uninominale_camera_id')->references('id')->on('candidature_collegi_uninominale_camera');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidature_collegi_uninominale_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coalizione_id')->references('id')->on('coalizioni');
            $table->unsignedBigInteger('collegio_uninominale_senato_id')->references('id')->on('collegi_plurinominali_senato');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedInteger('numero');
        });

        Schema::create('candidature_collegi_uninominale_senato_liste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->nullable()->references('id')->on('liste');
            $table->unsignedBigInteger('candidatura_collegio_uninominale_senato_id')->references('id')->on('candidature_collegi_uninominale_senato');
            $table->unsignedInteger('numero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidati');
        Schema::dropIfExists('liste');
        Schema::dropIfExists('coalizioni');
        Schema::dropIfExists('candidature_collegi_plurinominali_camera');
        Schema::dropIfExists('candidati_candidature_collegi_plurinominali_camera');
        Schema::dropIfExists('candidature_collegi_plurinominali_senato');
        Schema::dropIfExists('candidati_candidature_collegi_plurinominali_senato');
        Schema::dropIfExists('candidature_collegi_uninominale_camera');
        Schema::dropIfExists('candidature_collegi_uninominale_senato');
        Schema::dropIfExists('candidature_collegi_uninominale_camera_liste');
        Schema::dropIfExists('candidature_collegi_uninominale_senato_liste');
    }
};
