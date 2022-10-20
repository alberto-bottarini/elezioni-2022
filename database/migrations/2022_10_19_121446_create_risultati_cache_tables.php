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
        Schema::create('risultati_collegi_plurinominali_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_camera');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_collegi_plurinominali_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_id')->references('id')->on('collegi_plurinominali_senato');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_circoscrizioni_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circoscrizione_id')->references('id')->on('circoscrizioni_camera');
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_circoscrizioni_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circoscrizione_id')->references('id')->on('circoscrizioni_senato');
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
        Schema::dropIfExists('risultati_collegi_plurinominali_camera');
        Schema::dropIfExists('risultati_collegi_plurinominali_senato');
        Schema::dropIfExists('risultati_circoscrizioni_camera');
        Schema::dropIfExists('risultati_circoscrizioni_senato');
    }
};