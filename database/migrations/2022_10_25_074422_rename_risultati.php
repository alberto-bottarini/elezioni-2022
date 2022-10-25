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
        Schema::rename('risultati_candidature_collegi_uninominale_camera', 'voti_candidature_camera_comuni');
        Schema::rename('risultati_candidature_collegi_uninominale_camera_liste', 'voti_candidature_camera_comuni_liste');
        Schema::rename('risultati_candidature_collegi_uninominale_senato', 'voti_candidature_senato_comuni');
        Schema::rename('risultati_candidature_collegi_uninominale_senato_liste', 'voti_candidature_senato_comuni_liste');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('voti_candidature_camera_comuni', 'risultati_candidature_collegi_uninominale_camera');
        Schema::rename('voti_candidature_camera_comuni_liste', 'risultati_candidature_collegi_uninominale_camera_liste');
        Schema::rename('voti_candidature_senato_comuni', 'risultati_candidature_collegi_uninominale_senato');
        Schema::rename('voti_candidature_senato_comuni_liste', 'risultati_candidature_collegi_uninominale_senato_liste');
    }
};
