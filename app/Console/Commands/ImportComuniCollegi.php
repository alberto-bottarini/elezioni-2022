<?php

namespace App\Console\Commands;

use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Comune;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportComuniCollegi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-comuni-collegi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Comuni e Collegi';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Importo dati CAMERA');

        $cameraFile = '/var/data/elenco_collegi_comuni_camera_27-09-2022.csv';
        $cameraCsv = Reader::createFromPath($cameraFile, 'r');
        $cameraCsv->setDelimiter(';');

        $records = $cameraCsv->getRecords(['circoscrizione', 'collegio_plurinominale', 'collegio_uninominale', 'comune', 'provincia']);

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach($records as $row) {
            $circoscrizioneName = $row['circoscrizione'];
            $collegioPluriNome = $row['collegio_plurinominale'];
            $collegioUniNome = $row['collegio_uninominale'];
            $comuneNome = $row['comune'];
            $provinciaNome = $row['provincia'];

            CircoscrizioneCamera::unguard();
            $circoscrizione = CircoscrizioneCamera::firstOrCreate([
                'nome' => $circoscrizioneName
            ]);

            CollegioPlurinominaleCamera::unguard();
            $collegioPlurinominale = CollegioPlurinominaleCamera::firstOrCreate([
                'nome' => $collegioPluriNome,
                'circoscrizione_id' => $circoscrizione->id
            ]);

            CollegioUninominaleCamera::unguard();
            $collegioUninominale = CollegioUninominaleCamera::firstOrCreate([
                'nome' => $collegioUniNome,
                'collegio_plurinominale_id' => $collegioPlurinominale->id
            ]);

            Comune::unguard();
            $comune = Comune::updateOrCreate([
                'nome' => $comuneNome,
                'provincia' => $provinciaNome,
            ], [
                'collegio_uninominale_camera_id' => $collegioUninominale->id
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        
        $this->line('Importo dati SENATO');

        $senatoFile = '/var/data/elenco_collegi_comuni_senato_27-09-2022.csv';
        $senatoCsv = Reader::createFromPath($senatoFile, 'r');
        $senatoCsv->setDelimiter(';');

        $records = $senatoCsv->getRecords(['circoscrizione', 'collegio_plurinominale', 'collegio_uninominale', 'comune', 'provincia']);

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach($records as $row) {
            $circoscrizioneName = $row['circoscrizione'];
            $collegioPluriNome = $row['collegio_plurinominale'];
            $collegioUniNome = $row['collegio_uninominale'];
            $comuneNome = $row['comune'];
            $provinciaNome = $row['provincia'];

            CircoscrizioneSenato::unguard();
            $circoscrizione = CircoscrizioneSenato::firstOrCreate([
                'nome' => $circoscrizioneName
            ]);

            CollegioPlurinominaleSenato::unguard();
            $collegioPlurinominale = CollegioPlurinominaleSenato::firstOrCreate([
                'nome' => $collegioPluriNome,
                'circoscrizione_id' => $circoscrizione->id
            ]);

            CollegioUninominaleSenato::unguard();
            $collegioUninominale = CollegioUninominaleSenato::firstOrCreate([
                'nome' => $collegioUniNome,
                'collegio_plurinominale_id' => $collegioPlurinominale->id
            ]);

            Comune::unguard();
            $comune = Comune::updateOrCreate([
                'nome' => $comuneNome,
                'provincia' => $provinciaNome,
            ], [
                'collegio_uninominale_senato_id' => $collegioUninominale->id
            ]);

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
