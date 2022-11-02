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
        Schema::table('candidature_collegi_uninominale_camera', function (Blueprint $table) {
            $table->boolean('eletto');
        });

        Schema::table('candidati_candidature_collegi_plurinominali_camera', function (Blueprint $table) {
            $table->boolean('eletto');
        });

        Schema::table('candidature_collegi_uninominale_senato', function (Blueprint $table) {
            $table->boolean('eletto');
        });

        Schema::table('candidati_candidature_collegi_plurinominali_senato', function (Blueprint $table) {
            $table->boolean('eletto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidature_collegi_uninominale_camera', function (Blueprint $table) {
            $table->dropColumn('eletto');
        });

        Schema::table('candidati_candidature_collegi_plurinominali_camera', function (Blueprint $table) {
            $table->dropColumn('eletto');
        });

        Schema::table('candidature_collegi_uninominale_senato', function (Blueprint $table) {
            $table->dropColumn('eletto');
        });

        Schema::table('candidati_candidature_collegi_plurinominali_senato', function (Blueprint $table) {
            $table->dropColumn('eletto');
        });
    }
};
