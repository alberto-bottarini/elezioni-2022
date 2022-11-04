<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CandidaturaEsteroCamera;
use App\Models\CandidaturaEsteroSenato;
use App\Models\Lista;
use App\Models\NazioneEstero;
use App\Models\PreferenzaEsteroCamera;
use App\Models\PreferenzaEsteroSenato;
use App\Models\RipartizioneEstero;
use App\Models\VotoListaEsteroCamera;
use App\Models\VotoListaEsteroSenato;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportEstero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-estero';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Estero';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $palazzi = ['camera', 'senato'];
        foreach ($palazzi as $palazzo) {
            if ($palazzo == 'camera') {
                $candidaturaModel = CandidaturaEsteroCamera::class;
                $preferenzaModel = PreferenzaEsteroCamera::class;
                $votoModel = VotoListaEsteroCamera::class;
            } else {
                $candidaturaModel = CandidaturaEsteroSenato::class;
                $preferenzaModel = PreferenzaEsteroSenato::class;
                $votoModel = VotoListaEsteroSenato::class;
            }

            $this->warn('Importo candidature ' . $palazzo);
            $candidatureFile = '/var/eligendo/'.strtoupper($palazzo).'_ESTERO_20220925.csv';
            $candidatureCsv = Reader::createFromPath($candidatureFile, 'r');
            $candidatureCsv->setHeaderOffset(0);

            $records = $candidatureCsv->getRecords();

            $bar = $this->output->createProgressBar(iterator_count($records));
            $bar->start();

            foreach ($records as $record) {
                $ripartizioneNome = $record['RIPARTIZIONE'];
                RipartizioneEstero::unguard();
                $ripartizione = RipartizioneEstero::firstOrCreate([
                    'nome' => $ripartizioneNome
                ]);

                $candidatoNome = $record['CANDIDATO'];
                Candidato::unguard();
                $candidato = Candidato::updateOrCreate([
                    'nome' => $candidatoNome
                ], [
                    'data_nascita' => $record['DATA_NASCITA'],
                    'luogo_nascita' => $record['LUOGO_NASCITA']
                ]);

                $listaNome = $record['DESCR_LISTA'];
                Lista::unguard();
                $lista = Lista::firstOrCreate([
                    'nome' => $listaNome
                ]);

                $candidaturaModel::unguard();
                $candidaturaModel::firstOrCreate([
                    'ripartizione_id' => $ripartizione->id,
                    'candidato_id' => $candidato->id,
                    'lista_id' => $lista->id
                ], [
                    'eletto' => false
                ]);

                $bar->advance();
            }

            $this->newLine();

            $this->warn('Importo preferenze ' . $palazzo);

            $preferenzeFile = '/var/eligendo/Politiche2022_Preferenze_'.ucfirst($palazzo).'_Estero.csv';
            $preferenzeCsv = Reader::createFromPath($preferenzeFile, 'r');
            $preferenzeCsv->setHeaderOffset(0);
            $preferenzeCsv->setDelimiter(';');

            $records = $preferenzeCsv->getRecords();

            $bar = $this->output->createProgressBar(iterator_count($records));
            $bar->start();

            foreach ($records as $record) {
                $ripartizioneNome = $record['Ripartizione'];
                $ripartizione = RipartizioneEstero::where('nome', $ripartizioneNome)->first();
                if (is_null($ripartizione)) {
                    $this->error('Ripartizione ' . $ripartizioneNome . ' non esiste');
                    return;
                }

                $nazioneNome = $record['Nazione'];
                NazioneEstero::unguard();
                $nazione = NazioneEstero::firstOrCreate([
                    'nome' => $nazioneNome,
                    'ripartizione_id' => $ripartizione->id
                ]);

                $candidatoNome = $record['Nome'] . ' ' . $record['Cognome'];
                if ($candidatoNome == 'LUIS ROBERTO DI SAN MARTINO LORENZATO DI IVREA') {
                    $candidatoNome = 'LUIS ROBERTO DI SAN MARTINO LORENZATO DI IVREA DETTO LORENZATO';
                } elseif ($candidatoNome == 'ANGELA NISSOLI') {
                    $candidatoNome = 'ANGELA NISSOLI DETTA FUCSIA DETTA FITZGERALD';
                } elseif ($candidatoNome == 'GIUSEPPE CANCIANI') {
                    $candidatoNome = 'GIUSEPPE CANCIANI DETTO PAOLO';
                } elseif ($candidatoNome == 'PASQUALE CAPRIATI') {
                    $candidatoNome = 'PASQUALE CAPRIATI DETTO PAT';
                } elseif ($candidatoNome == 'GIUSEPPE COSSARI') {
                    $candidatoNome = 'GIUSEPPE COSSARI DETTO JOE';
                }


                $candidato = Candidato::where('nome', $candidatoNome)->first();
                if (is_null($candidato)) {
                    $this->error('Candidato ' . $candidatoNome . ' non esiste');
                    return;
                }

                $listaNome = $record['Lista'];
                $lista = Lista::where('nome', $listaNome)->first();
                if (is_null($lista)) {
                    $this->error('Lista ' . $listaNome . ' non esiste');
                    return;
                }

                $candidatura = $candidaturaModel::where('candidato_id', $candidato->id)
                    ->where('lista_id', $lista->id)
                    ->where('ripartizione_id', $ripartizione->id)
                    ->first();
                if (is_null($candidatura)) {
                    $this->error('Candidatura non esiste');
                    return;
                }

                $preferenzaModel::updateOrCreate([
                    'candidatura_id' => $candidatura->id,
                    'nazione_id' => $nazione->id
                ], [
                    'preferenze' => $record['Preferenze']
                ]);


                $bar->advance();
            }

            $this->newLine();

            $this->warn('Importo voti ' . $palazzo);

            $scrutiniFile = '/var/eligendo/Politiche2022_Scrutini_'.ucfirst($palazzo).'_Estero.csv';
            $scrutiniCsv = Reader::createFromPath($scrutiniFile, 'r');
            $scrutiniCsv->setHeaderOffset(0);
            $scrutiniCsv->setDelimiter(';');

            $records = $scrutiniCsv->getRecords();

            $bar = $this->output->createProgressBar(iterator_count($records));
            $bar->start();

            $votiCollection = collect();
            foreach ($records as $record) {
                $listaNome = $record['Lista'];
                $lista = Lista::where('nome', $listaNome)->first();
                if (is_null($lista)) {
                    $this->error('Lista ' . $listaNome . ' non esiste');
                    return;
                }

                $nazioneNome = $record['Nazione'];
                $nazione = NazioneEstero::where('nome', $nazioneNome)->first();
                if (is_null($nazione)) {
                    $this->error('Nazione ' . $nazioneNome . ' non esiste');
                    return;
                }

                if (!$votiCollection->has($nazione->id)) {
                    $votiCollection->put($nazione->id, collect());
                }

                $nazioneCollection = $votiCollection->get($nazione->id);
                $nazioneCollection->put($lista->id, $record['Voti Liste']);
                $votiCollection->put($nazione->id, $nazioneCollection);

                $bar->advance();
            }

            foreach ($votiCollection as $nazioneId => $nazioneCollection) {
                $totale = $nazioneCollection->sum();
                foreach ($nazioneCollection as $listaId => $voti) {
                    $votoModel::unguard();
                    $votoModel::updateOrCreate([
                        'lista_id' => $listaId,
                        'nazione_id' => $nazioneId
                    ], [
                        'voti' => $voti,
                        'percentuale' => $totale > 0 ? $voti / $totale * 100 : 0
                    ]);
                }
            }


            $this->newLine();
        }


        return Command::SUCCESS;
    }
}
