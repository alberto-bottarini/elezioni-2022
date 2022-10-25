<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risultati_collegi_uninominale_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_collegi_uninominale_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('candidato_id')->references('id')->on('candidati');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_collegi_uninominale_camera_liste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_collegi_uninominale_senato_liste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risultati_collegi_uninominale_camera');
        Schema::dropIfExists('risultati_collegi_uninominale_senato');
        Schema::dropIfExists('risultati_collegi_uninominale_camera_liste');
        Schema::dropIfExists('risultati_collegi_uninominale_senato_liste');
    }
};
