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
        Schema::create('comuni', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('provincia');
            //$table->unsignedBigInteger('collegio_uninominale_camera_id')->nullable()->references('id')->on('collegi_uninominali_camera');
            //$table->unsignedBigInteger('collegio_uninominale_senato_id')->nullable()->references('id')->on('collegi_uninominali_senato');
        });

        Schema::create('circoscrizioni_camera', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('collegi_plurinominali_camera', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('circoscrizione_id')->references('id')->on('circoscrizioni_camera');
        });

        Schema::create('collegi_uninominali_camera', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('collegio_plurinominale_id')->references('id')->on('collegi_plurinominali_camera');
        });

        Schema::create('circoscrizioni_senato', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('collegi_plurinominali_senato', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('circoscrizione_id')->references('id')->on('circoscrizioni_senato');
        });

        Schema::create('collegi_uninominali_senato', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('collegio_plurinominale_id')->references('id')->on('collegi_plurinominali_senato');
        });

        Schema::create('collegi_uninominali_camera_comuni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_uninominale_camera_id')->references('id')->on('collegi_uninominali_camera');
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
        });

        Schema::create('collegi_uninominali_senato_comuni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collegio_uninominale_senato_id')->references('id')->on('collegi_uninominali_senato');
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comuni');
        Schema::dropIfExists('circoscrizioni_camera');
        Schema::dropIfExists('collegi_plurinominali_camera');
        Schema::dropIfExists('collegi_uninominali_camera');
        Schema::dropIfExists('circoscrizioni_senato');
        Schema::dropIfExists('collegi_plurinominali_senato');
        Schema::dropIfExists('collegi_uninominali_senato');
    }
};
