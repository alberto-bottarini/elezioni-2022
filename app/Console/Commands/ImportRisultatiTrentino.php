<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CandidaturaCollegioUninominaleSenato;
use App\Models\Comune;
use App\Models\VotoCandidaturaSenatoComune;
use App\Models\VotoCandidaturaSenatoComuneLista;
use Goutte\Client;
use Illuminate\Console\Command;

class ImportRisultatiTrentino extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-risultati-tta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Risultati Trentino';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $urls = [
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/amblar-don/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/andalo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/borgo-d-anaunia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/bresimo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/caldes/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/campodenno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavareno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavedago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavedine/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavizzana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cembra-lisignago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cimone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cles/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/commezzadura/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/conta/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/croviana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/dambel/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/denno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/dimaro-folgarida/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/fai-della-paganella/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/garniga-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/giovo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/lavis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/livo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/lona-lases/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/madruzzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/male/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/mezzana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/mezzocorona/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ville-d-anaunia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/albiano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/aldeno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/altavalle/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/amblar-don/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/andalo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/borgo-d-anaunia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/bresimo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/caldes/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/campodenno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavareno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavedago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavedine/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cavizzana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cembra-lisignago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cimone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/cles/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/commezzadura/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/conta/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/croviana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/dambel/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/denno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/dimaro-folgarida/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/fai-della-paganella/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/garniga-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/giovo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/lavis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/livo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/lona-lases/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/madruzzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/male/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/mezzana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/mezzocorona/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/mezzolombardo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/molveno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/novella/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ossana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/peio/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/pellizzano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/predaia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/rabbi/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/romeno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ronzone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/rovere-della-luna/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ruffre-mendola/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/rumo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/san-michele-all-adige/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/sanzeno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/sarnonico/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/segonzano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/sfruz/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/sover/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/spormaggiore/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/sporminore/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/terre-d-adige/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/terzolas/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ton/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/trento/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/vallelaghi/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/vermiglio/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u01/ville-d-anaunia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/ala/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/arco/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/avio/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/besenello/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/bleggio-superiore/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/bocenago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/bondone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/borgo-chiese/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/borgo-lares/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/brentonico/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/caderzone-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/calliano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/carisolo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/castel-condino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/comano-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/drena/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/dro/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/fiave/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/folgaria/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/giustino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/isera/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/ledro/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/massimeno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/mori/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/nago-torbole/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/nogaredo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/nomi/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/pelugo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/pieve-di-bono-prezzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/pinzolo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/pomarolo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/porte-di-rendena/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/riva-del-garda/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/ronzo-chienis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/rovereto/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/san-lorenzo-dorsino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/sella-giudicarie/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/spiazzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/stenico/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/storo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/strembo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tenno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/terragnolo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tione-di-trento/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/trambileno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tre-ville/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/valdaone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/vallarsa/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/villa-lagarina/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/volano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/altopiano-della-vigolana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/baselga-di-pine/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/bedollo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/bieno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/borgo-valsugana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/calceranica-al-lago/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/caldonazzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/campitello-di-fassa/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/canal-san-bovo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/canazei/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/capriana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/carzano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/castel-ivano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/castello-tesino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/castello-molina-di-fiemme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/castelnuovo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/cavalese/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/cinte-tesino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/civezzano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/fierozzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/fornace/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/frassilongo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/grigno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/imer/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/lavarone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/levico-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/luserna/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/mazzin/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/mezzano/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/moena/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/novaledo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/ospedaletto/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/palu-del-fersina/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/panchia/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/pergine-valsugana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/pieve-tesino/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/predazzo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/primiero-san-martino-di-castrozza/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/roncegno-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/ronchi-valsugana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/sagron-mis/",
            //"https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/samone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/san-giovanni-di-fassa/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/sant-orsola-terme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/scurelle/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/soraga-di-fassa/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/telve/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/telve-di-sopra/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/tenna/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/tesero/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/torcegno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/valfloriana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/vignola-falesina/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/ville-di-fiemme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u03/ziano-di-fiemme/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/aldino_aldein/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/aldino_aldein/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/anterivo_altrei/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/appiano-sulla-strada-del-vino_eppan-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/bolzano_bozen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/bronzolo_branzoll/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/caldaro-sulla-strada-del-vino_kaltern-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/cornedo-all-isarco_karneid/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/cortaccia-sulla-strada-del-vino_kurtatsch-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/cortina-sulla-strada-del-vino_kurtinig-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/egna_neumarkt/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/laives_leifers/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/magre-sulla-strada-del-vino_margreid-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/montagna_montan/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/ora_auer/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/salorno-sulla-strada-del-vino_salurn-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/termeno-sulla-strada-del-vino_tramin-an-der-weinstrasse/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/trodena-nel-parco-naturale_truden-im-naturpark/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u04/vadena_pfatten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/andriano_andrian/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/avelengo_hafling/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/caines_kuens/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/castelbello-ciardes_kastelbell-tschars/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/cermes_tscherms/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/curon-venosta_graun-im-vinschgau/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/gargazzone_gargazon/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/glorenza_glurns/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/laces_latsch/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/lagundo_algund/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/lana_lana/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/lasa_laas/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/lauregno_laurein/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/malles-venosta_mals/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/marlengo_marling/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/martello_martell/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/meltina_molten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/merano_meran/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/moso-in-passiria_moos-in-passeier/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/nalles_nals/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/naturno_naturns/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/parcines_partschins/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/plaus_plaus/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/postal_burgstall/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/prato-allo-stelvio_prad-am-stilfser-joch/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/proves_proveis/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/rifiano_riffian/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/san-genesio-atesino_jenesien/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/san-leonardo-in-passiria_st.-leonhard-in-passeier/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/san-martino-in-passiria_st.-martin-in-passeier/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/san-pancrazio_st.-pankraz/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/sarentino_sarntal/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/scena_schenna/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/senale-san-felice_unsere-liebe-frau-im-walde-st.-felix/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/senales_schnals/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/silandro_schlanders/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/sluderno_schluderns/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/stelvio_stilfs/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/terlano_terlan/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/tesimo_tisens/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/tirolo_tirol/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/tubre_taufers-im-munstertal/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/ultimo_ulten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/verano_voran/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/badia_abtei/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/barbiano_barbian/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/braies_prags/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/brennero_brenner/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/bressanone_brixen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/brunico_bruneck/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/campo-di-trens_freienfeld/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/campo-tures_sand-in-taufers/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/castelrotto_kastelruth/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/chienes_kiens/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/chiusa_klausen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/corvara-in-badia_corvara/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/dobbiaco_toblach/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/falzes_pfalzen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/fie-allo-sciliar_vols-am-schlern/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/fortezza_franzensfeste/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/funes_villnoss/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/gais_gais/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/la-valle_wengen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/laion_lajen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/luson_lusen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/marebbe_enneberg/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/monguelfo-tesido_welsberg-taisten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/naz-sciaves_natz-schabs/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/nova-levante_welschnofen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/nova-ponente_deutschnofen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/ortisei_st.-ulrich/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/perca_percha/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/ponte-gardena_waidbruck/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/predoi_prettau/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/racines_ratschings/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/rasun-anterselva_rasen-antholz/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/renon_ritten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/rio-di-pusteria_muhlbach/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/rodengo_rodeneck/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/san-candido_innichen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/san-lorenzo-di-sebato_st.-lorenzen/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/san-martino-in-badia_st.-martin-in-thurn/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/santa-cristina-valgardena_st.-christina-in-groden/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/selva-dei-molini_muhlwald/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/selva-di-val-gardena_wolkenstein-in-groden/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/sesto_sexten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/terento_terenten/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/tires_tiers/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/val-di-vizze_pfitsch/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/valdaora_olang/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/valle-aurina_ahrntal/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/valle-di-casies_gsies/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/vandoies_vintl/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/varna_vahrn/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/velturno_feldthurns/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/villabassa_niederdorf/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/villandro_villanders/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u06/vipiteno_sterzing/",
        ];


        $urls = [
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tenno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/terragnolo/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tione-di-trento/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/trambileno/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/tre-ville/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/valdaone/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/vallarsa/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/villa-lagarina/",
            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u02/volano/",

            "https://elezioni.repubblica.it/2022/senatodellarepubblica/trentino-alto-adige_sudtirol/trentino-alto-adige_sudtirol/trentino-altoadige_sudtirol-u05/verano_voran/"
        ];

        $client = new Client();

        foreach ($urls as $url) {
            $this->info($url);
            $crawler = $client->request('GET', $url);
            $nomeComune = $crawler->filter('[itemprop="name"][aria-label="Comune"]')->text();

            if ($nomeComune == 'contà') {
                $nomeComune = 'CONTA\'';
            } elseif ($nomeComune == 'malè') {
                $nomeComune = 'MALE\'';
            } elseif ($nomeComune == 'roverè della luna') {
                $nomeComune = 'ROVERE\' DELLA LUNA';
            } elseif ($nomeComune == 'ruffrè-mendola') {
                $nomeComune = 'RUFFRE\'-MENDOLA';
            } elseif ($nomeComune == 'fiavè') {
                $nomeComune = 'FIAVE\'';
            } elseif ($nomeComune == 'baselga di pinè') {
                $nomeComune = 'BASELGA DI PINE\'';
            } elseif ($nomeComune == 'palù') {
                $nomeComune = 'PALU\'';
            } elseif ($nomeComune == 'panchià') {
                $nomeComune = 'PANCHIA\'';
            } elseif ($nomeComune == 'magrè sulla strada del vino/margreid an der weinstrasse') {
                $nomeComune = 'MAGRE\' SULLA STRADA DEL VINO/MARGREID AN DER WEINSTRASSE';
            } elseif ($nomeComune == 'palù del fersina') {
                $nomeComune = 'PALU\' DEL FERSINA';
            } else if($nomeComune == 'fiè allo sciliar/völs am schlern') {
                $nomeComune = 'FIE\' ALLO SCILIAR/VOLS AM SCHLERN';
            }

            $comuni = Comune::where('nome', $nomeComune)
                ->whereIn('provincia', ['BZ', 'TN'])
                ->get();

            if ($comuni->count() == 0) {
                $this->error($nomeComune . ' non trovato');
                continue;
            }

            if ($comuni->count() > 1) {
                $this->error($nomeComune . ' ha duplicati');
                continue;
            }

            $comune = $comuni->first();
            $collegioUninominale = $comune->collegiUninominaliSenato()->first();


            $rows = $crawler->filter('.tutti .stonda');
            $rows->each(function ($row) use ($collegioUninominale, $comune) {
                $nomeCandidato = $row->filter('.img-circle')->attr('title');

                $candidati = Candidato::where('nome', $nomeCandidato)
                    ->get();

                if ($candidati->count() == 0) {
                    $this->error($nomeCandidato . ' non trovato');
                    return;
                }

                $candidato = $candidati->first();

                $voti = $row->filter('[data-name="placeholder_2"]')->text();
                $voti = str_replace('.', '', $voti);
                if ($voti == '-') {
                    $voti = 0;
                }

                $candidatura = $collegioUninominale->candidature()->where('candidato_id', $candidato->id)->first();

                VotoCandidaturaSenatoComune::unguard();
                VotoCandidaturaSenatoComune::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_id' => $candidatura->id,
                ], [
                    'voti_candidato' => 0,
                    'voti' => $voti,
                ]);

                $candidaturaLista = $candidatura->candidatureLista()->first();

                VotoCandidaturaSenatoComuneLista::unguard();
                VotoCandidaturaSenatoComuneLista::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_lista_id' => $candidaturaLista->id,
                ], [
                    'voti' => $voti,
                ]);
            });
        }
    }
}
