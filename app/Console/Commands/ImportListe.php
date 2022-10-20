<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CandidaturaCollegioPlurinominaleCamera;
use App\Models\CandidaturaCollegioPlurinominaleSenato;
use App\Models\CandidaturaCollegioUninominaleCamera;
use App\Models\CandidaturaCollegioUninominaleCameraLista;
use App\Models\CandidaturaCollegioUninominaleSenato;
use App\Models\Coalizione;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Lista;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportListe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-liste';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Liste';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Importo dati CAMERA PLURINOMINALE');

        $cameraFile = '/var/eligendo/CAMERA_ITALIA_20220925_pluri.csv';
        $cameraCsv = Reader::createFromPath($cameraFile, 'r');
        $cameraCsv->setHeaderOffset(0);

        $records = $cameraCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $numeroLista = 1;
        $numeroCandidato = 1;
        foreach ($records as $record) {
            $collegio = CollegioPlurinominaleCamera::where('nome', $record['COLLEGIO_PLURINOMINALE'])->first();

            if (!$collegio) {
                $this->error('Collegio Plurinominale Camera non trovato: ' . $record['COLLEGIO_PLURINOMINALE']);
                continue;
            }

            Lista::unguard();
            $lista = Lista::firstOrCreate([
                'nome' => trim($record['DESCR_LISTA']),
            ]);

            $nomeCandidato = str_replace('  ', ' ', $record['CANDIDATO']);
            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $nomeCandidato,
            ], [
                'data_nascita' => $record['DATA_NASCITA'],
                'luogo_nascita' => $record['LUOGO_NASCITA'],
            ]);

            CandidaturaCollegioPlurinominaleCamera::unguard();
            $candidatura = CandidaturaCollegioPlurinominaleCamera::firstOrCreate([
                'lista_id' => $lista->id,
                'collegio_plurinominale_camera_id' => $collegio->id,
            ], [
                'numero' => $numeroLista++,
            ]);

            if (!$candidato->candidatureCollegiPlurinominaliCamera()->where('candidatura_collegio_plurinominale_camera_id', $candidatura->id)->exists()) {
                $candidato->candidatureCollegiPlurinominaliCamera()->attach($candidatura, ['numero' => $numeroCandidato++]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati SENATO PLURINOMINALE');

        $senatoFile = '/var/eligendo/SENATO_ITALIA_20220925_pluri.csv';
        $senatoCsv = Reader::createFromPath($senatoFile, 'r');
        $senatoCsv->setHeaderOffset(0);

        $records = $senatoCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $numeroLista = 1;
        $numeroCandidato = 1;
        foreach ($records as $record) {
            $collegio = CollegioPlurinominaleSenato::where('nome', $record['COLLEGIO_PLURINOMINALE'])->first();

            if (!$collegio) {
                $this->error('Collegio Plurinominale Senato non trovato: ' . $record['COLLEGIO_PLURINOMINALE']);
                continue;
            }

            Lista::unguard();
            $lista = Lista::firstOrCreate([
                'nome' => trim($record['DESCR_LISTA']),
            ]);

            $nomeCandidato = str_replace('  ', ' ', $record['CANDIDATO']);
            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $nomeCandidato,
            ], [
                'data_nascita' => $record['DATA_NASCITA'],
                'luogo_nascita' => $record['LUOGO_NASCITA'],
            ]);

            CandidaturaCollegioPlurinominaleSenato::unguard();
            $candidatura = CandidaturaCollegioPlurinominaleSenato::firstOrCreate([
                'lista_id' => $lista->id,
                'collegio_plurinominale_senato_id' => $collegio->id,
            ], [
                'numero' => $numeroLista++,
            ]);

            if (!$candidato->candidatureCollegiPlurinominaliSenato()->where('candidatura_collegio_plurinominale_senato_id', $candidatura->id)->exists()) {
                $candidato->candidatureCollegiPlurinominaliSenato()->attach($candidatura, ['numero' => $numeroCandidato++]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati CAMERA UNINOMINALE');

        $cameraFile = '/var/eligendo/CAMERA_ITALIA_20220925_uni.csv';
        $cameraCsv = Reader::createFromPath($cameraFile, 'r');
        $cameraCsv->setHeaderOffset(0);

        $records = $cameraCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $cameraCollection = collect(iterator_to_array($records));
        $groupedCollection = $cameraCollection->groupBy(function ($record) {
            return $record['COLLEGIO_UNINOMINALE'] . '$$$' . $record['CANDIDATO'];
        });

        $numeroLista = 1;
        foreach ($groupedCollection as $group) {
            $record = $group->first();
            $collegio = CollegioUninominaleCamera::where('nome', 'like', $record['COLLEGIO_UNINOMINALE'] . '%')->first();

            if (!$collegio) {
                $this->error('Collegio Uninominale Camera non trovato: ' . $record['COLLEGIO_UNINOMINALE']);
                continue;
            }

            $nomeCoalizione = $group->sortBy('DESCR_LISTA')->map(function ($record) {
                return trim($record['DESCR_LISTA']);
            })->join(' / ');

            Coalizione::unguard();
            $coalizione = Coalizione::firstOrCreate([
                'nome' => $nomeCoalizione,
            ]);

            $nomeCandidato = str_replace('  ', ' ', $record['CANDIDATO']);
            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $nomeCandidato,
            ], [
                'data_nascita' => $record['DATA_NASCITA'],
                'luogo_nascita' => $record['LUOGO_NASCITA'],
            ]);

            CandidaturaCollegioUninominaleCamera::unguard();
            $candidatura = CandidaturaCollegioUninominaleCamera::firstOrCreate([
                'coalizione_id' => $coalizione->id,
                'collegio_uninominale_camera_id' => $collegio->id,
                'candidato_id' => $candidato->id,
            ], [
                'numero' => $numeroLista++,
                'voti_candidato' => 0,
                'eletto' => false,
            ]);

            foreach ($group as $listaRecord) {
                Lista::unguard();

                $lista = Lista::firstOrCreate([
                    'nome' => trim($listaRecord['DESCR_LISTA']),
                ]);

                CandidaturaCollegioUninominaleCameraLista::updateOrCreate([
                    'lista_id' => $lista->id,
                    'candidatura_collegio_uninominale_camera_id' => $candidatura->id,
                ], [
                    'numero' => $numeroLista++,
                    'voti' => 0,
                    'percentuale' => 0,
                ]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati SENATO UNINOMINALE');

        $senatoFile = '/var/eligendo/SENATO_ITALIA_20220925_uni.csv';
        $senatoCsv = Reader::createFromPath($senatoFile, 'r');
        $senatoCsv->setHeaderOffset(0);

        $records = $senatoCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $senatoCollection = collect(iterator_to_array($records));
        $groupedCollection = $senatoCollection->groupBy(function ($record) {
            return $record['COLLEGIO_UNINOMINALE'] . '$$$' . $record['CANDIDATO'];
        });

        $numeroLista = 1;
        foreach ($groupedCollection as $group) {
            $record = $group->first();
            $collegio = CollegioUninominaleSenato::where('nome', 'like', $record['COLLEGIO_UNINOMINALE'] . '%')->first();

            if (!$collegio) {
                $this->error('Collegio Uninominale Senato non trovato: ' . $record['COLLEGIO_UNINOMINALE']);
                continue;
            }

            $nomeCoalizione = $group->sortBy('DESCR_LISTA')->map(function ($record) {
                return trim($record['DESCR_LISTA']);
            })->join(' / ');

            Coalizione::unguard();
            $coalizione = Coalizione::firstOrCreate([
                'nome' => $nomeCoalizione,
            ]);

            $nomeCandidato = str_replace('  ', ' ', $record['CANDIDATO']);
            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $nomeCandidato,
            ], [
                'data_nascita' => $record['DATA_NASCITA'],
                'luogo_nascita' => $record['LUOGO_NASCITA'],
            ]);

            CandidaturaCollegioUninominaleSenato::unguard();
            $candidatura = CandidaturaCollegioUninominaleSenato::firstOrCreate([
                'coalizione_id' => $coalizione->id,
                'collegio_uninominale_senato_id' => $collegio->id,
                'candidato_id' => $candidato->id,
            ], [
                'numero' => $numeroLista++,
                'voti_candidato' => 0,
                'eletto' => false,
            ]);

            foreach ($group as $listaRecord) {
                Lista::unguard();

                $lista = Lista::firstOrCreate([
                    'nome' => trim($listaRecord['DESCR_LISTA']),
                ]);

                $candidatura->liste()->attach($lista, ['numero' => $numeroLista++, 'voti' => 0, 'percentuale' => 0]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        return Command::SUCCESS;
    }
}
