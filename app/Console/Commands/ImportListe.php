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

        $cameraFile = '/var/data/elezioni-politiche-2022/liste/processing/CAMERA_ITALIA_20220925_pluri.csv';
        $cameraCsv = Reader::createFromPath($cameraFile, 'r');
        $cameraCsv->setHeaderOffset(0);

        $records = $cameraCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach ($records as $record) {
            $collegio = CollegioPlurinominaleCamera::where('nome', $record['desc_ente'])->firstOrFail();

            Lista::unguard();
            $lista = Lista::firstOrCreate([
                'nome' => $record['desc_lista'],
            ]);

            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $record['nome_c'],
                'cognome' => $record['cogn_c'],
            ], [
                'altro_1' => $record['altro_1'],
                'altro_2' => $record['altro_2'],
                'sesso' => $record['sesso'],
                'anno_nascita' => $record['dt_nasc'],
                'luogo_nascita' => $record['l_nasc'],
            ]);

            CandidaturaCollegioPlurinominaleCamera::unguard();
            $candidatura = CandidaturaCollegioPlurinominaleCamera::firstOrCreate([
                'lista_id' => $lista->id,
                'collegio_plurinominale_camera_id' => $collegio->id
            ], [
                'numero' => $record['num_lista']
            ]);

            if (!$candidato->candidatureCollegiPlurinominaliCamera()->where('candidatura_collegio_plurinominale_camera_id', $candidatura->id)->exists()) {
                $candidato->candidatureCollegiPlurinominaliCamera()->attach($candidatura, ['numero' => $record['num_c']]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati SENATO PLURINOMINALE');

        $senatoFile = '/var/data/elezioni-politiche-2022/liste/processing/SENATO_ITALIA_20220925_pluri.csv';
        $senatoCsv = Reader::createFromPath($senatoFile, 'r');
        $senatoCsv->setHeaderOffset(0);

        $records = $senatoCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach ($records as $record) {
            $collegio = CollegioPlurinominaleSenato::where('nome', $record['desc_ente'])->firstOrFail();

            Lista::unguard();
            $lista = Lista::firstOrCreate([
                'nome' => $record['desc_lista'],
            ]);

            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $record['nome_c'],
                'cognome' => $record['cogn_c'],
            ], [
                'altro_1' => $record['altro_1'],
                'altro_2' => $record['altro_2'],
                'sesso' => $record['sesso'],
                'anno_nascita' => $record['dt_nasc'],
                'luogo_nascita' => $record['l_nasc'],
            ]);

            CandidaturaCollegioPlurinominaleSenato::unguard();
            $candidatura = CandidaturaCollegioPlurinominaleSenato::firstOrCreate([
                'lista_id' => $lista->id,
                'collegio_plurinominale_senato_id' => $collegio->id
            ], [
                'numero' => $record['num_lista']
            ]);

            if (!$candidato->candidatureCollegiPlurinominaliSenato()->where('candidatura_collegio_plurinominale_senato_id', $candidatura->id)->exists()) {
                $candidato->candidatureCollegiPlurinominaliSenato()->attach($candidatura, ['numero' => $record['num_c']]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati CAMERA UNINOMINALE');

        $cameraFile = '/var/data/elezioni-politiche-2022/liste/processing/CAMERA_ITALIA_20220925_uni.csv';
        $cameraCsv = Reader::createFromPath($cameraFile, 'r');
        $cameraCsv->setHeaderOffset(0);

        $records = $cameraCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $listeFile = '/var/data/elezioni-politiche-2022/liste/processing/CAMERA_ITALIA_20220925_uni_coalizioni.csv';
        $listeCsv = Reader::createFromPath($listeFile, 'r');
        $listeCsv->setHeaderOffset(0);
        $listeRecords = $listeCsv->getRecords();
        $listeCollection = collect(iterator_to_array($listeRecords));

        $coalizioniFile = '/var/data/elezioni-politiche-2022/liste/processing/CAMERA_ITALIA_20220925_uni_coalizioni_nested.csv';
        $coalizioniCsv = Reader::createFromPath($coalizioniFile, 'r');
        $coalizioniCsv->setHeaderOffset(0);
        $coalizioniRecords = $coalizioniCsv->getRecords();
        $coalizioniCollection = collect(iterator_to_array($coalizioniRecords));

        foreach ($records as $record) {
            $collegio = CollegioUninominaleCamera::where('nome', 'like', $record['desc_ente'] . '%')->firstOrFail();

            $coalizioneRecord = $coalizioniCollection->firstWhere('cod_cand', $record['cod_cand']);

            Coalizione::unguard();
            $coalizione = Coalizione::firstOrCreate([
                'nome' => $coalizioneRecord['desc_lista'],
            ]);

            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $record['nome_c'],
                'cognome' => $record['cogn_c'],
            ], [
                'altro_1' => $record['altro_1'],
                'altro_2' => $record['altro_2'],
                'sesso' => $record['sesso'],
                'anno_nascita' => $record['dt_nasc'],
                'luogo_nascita' => $record['l_nasc'],
            ]);

            CandidaturaCollegioUninominaleCamera::unguard();
            $candidatura = CandidaturaCollegioUninominaleCamera::firstOrCreate([
                'coalizione_id' => $coalizione->id,
                'collegio_uninominale_camera_id' => $collegio->id,
                'candidato_id' => $candidato->id,
            ], [
                'numero' => $record['num_c'],
            ]);

            $listeRecord = $listeCollection->where('cod_cand', $record['cod_cand']);
            foreach ($listeRecord as $listaRecord) {
                Lista::unguard();

                $lista = Lista::firstOrCreate([
                    'nome' => $listaRecord['desc_lista'],
                ]);

                $candidatura->liste()->attach($lista, ['numero' => $listaRecord['num_lista']]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();

        $this->line('Importo dati SENATO UNINOMINALE');

        $senatoFile = '/var/data/elezioni-politiche-2022/liste/processing/SENATO_ITALIA_20220925_uni.csv';
        $senatoCsv = Reader::createFromPath($senatoFile, 'r');
        $senatoCsv->setHeaderOffset(0);

        $records = $senatoCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        $listeFile = '/var/data/elezioni-politiche-2022/liste/processing/SENATO_ITALIA_20220925_uni_coalizioni.csv';
        $listeCsv = Reader::createFromPath($listeFile, 'r');
        $listeCsv->setHeaderOffset(0);
        $listeRecords = $listeCsv->getRecords();
        $listeCollection = collect(iterator_to_array($listeRecords));

        $coalizioniFile = '/var/data/elezioni-politiche-2022/liste/processing/SENATO_ITALIA_20220925_uni_coalizioni_nested.csv';
        $coalizioniCsv = Reader::createFromPath($coalizioniFile, 'r');
        $coalizioniCsv->setHeaderOffset(0);
        $coalizioniRecords = $coalizioniCsv->getRecords();
        $coalizioniCollection = collect(iterator_to_array($coalizioniRecords));

        foreach ($records as $record) {
            $collegio = CollegioUninominaleSenato::where('nome', 'like', $record['desc_ente'] . '%')->firstOrFail();

            $coalizioneRecord = $coalizioniCollection->firstWhere('cod_cand', $record['cod_cand']);

            Coalizione::unguard();
            $coalizione = Coalizione::firstOrCreate([
                'nome' => $coalizioneRecord['desc_lista'],
            ]);

            Candidato::unguard();
            $candidato = Candidato::firstOrCreate([
                'nome' => $record['nome_c'],
                'cognome' => $record['cogn_c'],
            ], [
                'altro_1' => $record['altro_1'],
                'altro_2' => $record['altro_2'],
                'sesso' => $record['sesso'],
                'anno_nascita' => $record['dt_nasc'],
                'luogo_nascita' => $record['l_nasc'],
            ]);

            CandidaturaCollegioUninominaleSenato::unguard();
            $candidatura = CandidaturaCollegioUninominaleSenato::firstOrCreate([
                'coalizione_id' => $coalizione->id,
                'collegio_uninominale_senato_id' => $collegio->id,
                'candidato_id' => $candidato->id,
            ], [
                'numero' => $record['num_c'],
            ]);

            $listeRecord = $listeCollection->where('cod_cand', $record['cod_cand']);
            foreach ($listeRecord as $listaRecord) {
                Lista::unguard();

                $lista = Lista::firstOrCreate([
                    'nome' => $listaRecord['desc_lista'],
                ]);

                $candidatura->liste()->attach($lista, ['numero' => $listaRecord['num_lista']]);
            }

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
