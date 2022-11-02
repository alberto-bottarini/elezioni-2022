<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CandidaturaCollegioPlurinominaleCamera;
use App\Models\CandidaturaCollegioPlurinominaleSenato;
use App\Models\CandidaturaCollegioUninominaleCamera;
use App\Models\CandidaturaCollegioUninominaleSenato;
use App\Models\Coalizione;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Lista;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportEletti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-eletti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Eletti';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $camere = [
            'camera', 'senato'
        ];

        foreach ($camere as $camera) {
            if ($camera == 'camera') {
                $collegioUninominaleModel = CollegioUninominaleCamera::class;
                $candidaturaUninominaleModel = CandidaturaCollegioUninominaleCamera::class;
                $collegioUninominaleId = 'collegio_uninominale_camera_id';
                $collegioPlurinominaleModel = CollegioPlurinominaleCamera::class;
                $candidaturaPlurinominaleModel = CandidaturaCollegioPlurinominaleCamera::class;
                $collegioPlurinominaleId = 'collegio_plurinominale_camera_id';
            } else {
                $collegioUninominaleModel = CollegioUninominaleSenato::class;
                $candidaturaUninominaleModel = CandidaturaCollegioUninominaleSenato::class;
                $collegioUninominaleId = 'collegio_uninominale_senato_id';
                $collegioPlurinominaleModel = CollegioPlurinominaleSenato::class;
                $candidaturaPlurinominaleModel = CandidaturaCollegioPlurinominaleSenato::class;
                $collegioPlurinominaleId = 'collegio_plurinominale_senato_id';
            }

            $csv = '/var/eligendo/Politiche2022_Eletti_'.ucfirst($camera).'_Italia.csv';
            $cameraCsv = Reader::createFromPath($csv, 'r');
            $cameraCsv->setDelimiter(';');
            $cameraCsv->setHeaderOffset(0);

            $this->warn($camera);

            $records = $cameraCsv->getRecords();

            $bar = $this->output->createProgressBar(iterator_count($records));
            $bar->start();

            $prevCandidato = null;

            foreach ($records as $record) {
                $nomeCandidato = trim($record['Cognome e nome']);
                $parts = explode(' ', $nomeCandidato);
                $parts = array_map(fn ($a) => trim($a), $parts);

                if ($nomeCandidato == '') {
                    $candidato = $prevCandidato;
                } elseif (count($parts) < 3) {
                    if (count($parts) == 2) {
                        $parts = array_reverse($parts);
                        $nomeCandidato = implode(' ', $parts);
                    }

                    $candidati = Candidato::where('nome', $nomeCandidato)->get();

                    if ($candidati->count() > 1) {
                        $this->error('Candidato ' . $nomeCandidato . ' ha duplicati');
                        continue;
                    }

                    $candidato = $candidati->first();
                } else {
                    $count = 100;
                    $candidato = null;
                    do {
                        shuffle($parts);
                        $nomeCandidato = implode(' ', $parts);
                        $candidati = Candidato::where('nome', $nomeCandidato)->get();
                        if ($candidati->count() == 1) {
                            $candidato = $candidati->first();
                        }
                        $count--;
                    } while (is_null($candidato) || $count > 0);

                    if(is_null($candidato)) {
                        $this->error('Candidato ' . $nomeCandidato . ' non trovato');
                        continue;
                    }
                }


                if ($record['Tipo candidatura'] == 'Uninominale') {
                    $nomeCollegio = trim($record['Collegio']);

                    $collegio = $collegioUninominaleModel::where('nome', $nomeCollegio)->first();
                    if (!$collegio) {
                        $this->error('Collegio uni ' . $nomeCollegio . ' non trovato');
                        continue;
                    }

                    $nomeCoalizione = trim($record['Coalizione']);

                    if ($nomeCoalizione == 'LEGA PER SALVINI PREMIER - FORZA ITALIA - NOI MODERATI/LUPI-TOTI-BRUGNARO-UDC - FRATELLI D’ITALIA CON GIORGIA MELONI') {
                        $nomeCoalizione = 'COALIZIONE DI CENTRODESTRA';
                    } elseif ($nomeCoalizione == 'IMPEGNO CIVICO LUIGI DI MAIO-CENTRO DEMOCRATICO – ALLEANZA VERDI E SINISTRA – PARTITO DEMOCRATICO-ITALIA DEMOCRATICA E PROGRESSISTA - +EUROPA') {
                        $nomeCoalizione = 'COALIZIONE DI CENTROSINISTRA';
                    } elseif ($nomeCoalizione == 'LEGA PER SALVINI PREMIER - FORZA ITALIA - NOI MODERATI - FRATELLI D\'ITALIA') {
                        $nomeCoalizione = 'LEGA PER SALVINI PREMIER - FORZA ITALIA - NOI MODERATI - FRATELLI D\'ITALIA';
                    }

                    $coalizione = Coalizione::where('nome', $nomeCoalizione)->first();
                    if (!$coalizione) {
                        $this->error('Coalizione ' . $nomeCoalizione . ' non trovato in ' . json_encode($record) );
                        continue;
                    }

                    $candidaturaUninominaleModel::where('candidato_id', $candidato->id)
                        ->where($collegioUninominaleId, $collegio->id)
                        ->where('coalizione_id', $coalizione->id)
                        ->update(['eletto' => true]);
                } else {
                    $nomeCollegio = trim($record['Collegio']);

                    $collegio = $collegioPlurinominaleModel::where('nome', $nomeCollegio)->first();
                    if (!$collegio) {
                        $this->error('Collegio pluri ' . $nomeCollegio . ' non trovato');
                        continue;
                    }

                    $nomeLista = trim($record['Lista']);
                    $lista = Lista::where('nome', $nomeLista)->first();
                    if (!$lista) {
                        $this->error('Lista ' . $nomeLista . ' non trovato');
                        continue;
                    }

                    $candidaturePlurinominali = $candidaturaPlurinominaleModel::where($collegioPlurinominaleId, $collegio->id)
                        ->where('lista_id', $lista->id)
                        ->first();

                    $candidaturePlurinominali->candidati()->updateExistingPivot($candidato->id, [
                        'eletto' => true
                    ]);
                }

                $prevCandidato = $candidato;

                $bar->advance();
            }
        }
    }
}
