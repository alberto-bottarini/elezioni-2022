<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risultati_candidature_collegi_uninominale_camera', function ($table) {
            $table->id();
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
            $table->unsignedBigInteger('candidatura_id')->references('id')->on('candidature_collegi_uninominale_camera');
            $table->unsignedInteger('voti_candidato');
            $table->unsignedInteger('voti');
        });

        Schema::create('risultati_candidature_collegi_uninominale_camera_liste', function ($table) {
            $table->id();
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
            $table->unsignedBigInteger('candidatura_lista_id')->references('id')->on('candidature_collegi_uninominale_camera_liste');
            $table->unsignedInteger('voti');
        });

        Schema::create('risultati_candidature_collegi_uninominale_senato', function ($table) {
            $table->id();
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
            $table->unsignedBigInteger('candidatura_id')->references('id')->on('candidature_collegi_uninominale_senato');
            $table->unsignedInteger('voti_candidato');
            $table->unsignedInteger('voti');
        });

        Schema::create('risultati_candidature_collegi_uninominale_senato_liste', function ($table) {
            $table->id();
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
            $table->unsignedBigInteger('candidatura_lista_id')->references('id')->on('candidature_collegi_uninominale_senato_liste');
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
        Schema::dropIfExists('risultati_candidature_collegi_uninominale_camera');
        Schema::dropIfExists('risultati_candidature_collegi_uninominale_camera_liste');
        Schema::dropIfExists('risultati_candidature_collegi_uninominale_senato');
        Schema::dropIfExists('risultati_candidature_collegi_uninominale_senato_liste');
    }
};
