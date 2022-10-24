<?php

namespace App\Console\Commands;

use App\Models\CircoscrizioneCamera;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\RisultatoCamera;
use App\Models\RisultatoCircoscrizioneCamera;
use App\Models\RisultatoCollegioPlurinominaleCamera;
use Illuminate\Console\Command;

class CacheRisultati extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:cache-risultati';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CacheRisultati';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $lombardia1 = CollegioPlurinominaleCamera::find(6);
        // $lombardia1->collegiUninominali->each(function ($collegioUninominale) {
        //     //$this->info('Collegio: ' . $collegioUninominale->nome);
        //     foreach ($collegioUninominale->candidature as $candidatura) {
        //         //$this->info('  Candidato: ' . $candidatura->candidato->nome);
        //         //$this->info('  Voti candidato: ' . $candidatura->risultati->sum('voti_candidato'));
        //         //$this->info('  Voti: ' . $candidatura->risultati->sum('voti'));
        //         foreach ($candidatura->candidatureLista as $candidaturaLista) {
        //             //$this->info('    Lista: ' . $candidaturaLista->lista->nome);
        //             //$this->info('    Voti: ' . $candidaturaLista->risultati->sum('voti'));
        //         }
        //     }
        // });

        // return;

        CollegioPlurinominaleCamera::each(function ($collegioPlurinominale) {
            // CollegioPlurinominaleCamera::where('id', 5)->each(function ($collegioPlurinominale) {
            $risultati = $collegioPlurinominale->collegiUninominali->reduce(function ($carry, $collegioUninominale) {
          //      $risultati = $collegioPlurinominale->collegiUninominali->where('id', 15)->reduce(function ($carry, $collegioUninominale) {
                $sumPerCoalizione = $collegioUninominale->candidature->groupBy('coalizione_id')->map(function ($candidature) {
                    return $candidature->sum(function ($candidatura) {
                        return $candidatura->candidatureLista->sum(function ($candidaturaLista) {
                            return $candidaturaLista->risultati->sum('voti');
                        });
                    });
                });

                foreach ($collegioUninominale->candidature as $candidatura) {
                    $votiPerLista = collect();
                    //$this->warn($candidatura->coalizione->nome);
                    $votiCandidato = $candidatura->risultati->sum('voti_candidato');
                    $votiCoalizione = $sumPerCoalizione->get($candidatura->coalizione_id);
                    foreach ($candidatura->candidatureLista as $candidaturaLista) {
                        //$this->info($candidaturaLista->lista->nome);
                        $votiLista = $candidaturaLista->risultati->sum('voti');
                        $votiCalcolati = $votiCoalizione > 0 ? $votiLista + $votiCandidato * $votiLista / $votiCoalizione : 0;
                        $votiArrotondati = floor($votiCalcolati);
                        $resto = $votiCalcolati - $votiArrotondati;

                        //$this->info('Voti lista = ' . $votiArrotondati . ' (resto ' . $resto . ')');
                        $votiPerLista->put($candidaturaLista->lista->id, [
                            'voti' => $votiArrotondati,
                            'resto' => $resto,
                        ]);
                    }

                    //$this->newLine();
                    $votiPerListaNetto = $votiPerLista->sum(function ($voto) {
                        return $voto['voti'];
                    });
                    //$this->info('Voti candidato = ' . $votiCandidato);
                    //$this->info('Voti totali coalizione = ' . $votiCoalizione);
                    //$this->info('Totale voti da assegnare  = ' . $votiCandidato + $votiCoalizione);
                    //$this->info('Totale voti assegnati = ' . $votiPerListaNetto);
                    //$this->newLine();

                    $votiDaRiassegnare = $votiCandidato + $votiCoalizione - $votiPerListaNetto;
                    if ($votiDaRiassegnare > 0) {
                        $votiOrdinatiPerResto = $votiPerLista->sortByDesc(function ($voto) {
                            return $voto['resto'];
                        });
                        $listeDaRiassegnare = $votiOrdinatiPerResto->keys();
                        foreach (range(0, $votiDaRiassegnare - 1) as $i) {
                            $lista = $listeDaRiassegnare->get($i);
                            //$this->info('Assegno un voto in piu a ' . $lista);

                            $old = $votiPerLista->get($lista);
                            $votiPerLista->put($lista, [
                                'voti' => $old['voti'] + 1,
                                'resto' => $old['resto'],
                            ]);
                        }
                    }

                    foreach ($votiPerLista as $lista => $item) {
                        //$this->info('Lista ' . $lista . ' ha ' . $item['voti'] . ' voti');

                        if (!$carry->has($lista)) {
                            $carry->put($lista, $item['voti']);
                        } else {
                            $carry->put($lista, $carry->get($lista) + $item['voti']);
                        }
                    }
                }

                return $carry;
            }, collect());

            $totaliPerCollegio = $risultati->sum();

            foreach ($risultati as $lista => $voti) {
                RisultatoCollegioPlurinominaleCamera::unguard();
                RisultatoCollegioPlurinominaleCamera::updateOrCreate([
                    'lista_id' => $lista,
                    'collegio_id' => $collegioPlurinominale->id,
                ], [
                    'voti' => $voti,
                    'percentuale' => $voti > 0 ? round($voti / $totaliPerCollegio * 100, 2) : 0,
                ]);
            }
        });

        CircoscrizioneCamera::each(function ($circoscrizione) {
            $risultati = $circoscrizione->collegiPlurinominali->reduce(function ($carry, $collegioPlurinominale) {
                $risultatiCollegio = $collegioPlurinominale->risultati;

                foreach ($risultatiCollegio as $risultatoCollegio) {
                    if (!$carry->has($risultatoCollegio->lista_id)) {
                        $carry->put($risultatoCollegio->lista_id, 0);
                    }

                    $carry->put($risultatoCollegio->lista_id,
                        $carry->get($risultatoCollegio->lista_id) + $risultatoCollegio->voti);
                }

                return $carry;
            }, collect());

            $totaliPerCircoscrizione = $risultati->sum();

            foreach ($risultati as $lista => $voti) {
                RisultatoCircoscrizioneCamera::unguard();
                RisultatoCircoscrizioneCamera::updateOrCreate([
                    'lista_id' => $lista,
                    'circoscrizione_id' => $circoscrizione->id,
                ], [
                    'voti' => $voti,
                    'percentuale' => $voti > 0 ? round($voti / $totaliPerCircoscrizione * 100, 2) : 0,
                ]);
            }
        });

        $risultatiCamera = RisultatoCircoscrizioneCamera::all()->reduce(function ($carry, $risultatoCircoscrizioneCamera) {
            if (!$carry->has($risultatoCircoscrizioneCamera->lista_id)) {
                return $carry->put($risultatoCircoscrizioneCamera->lista_id, $risultatoCircoscrizioneCamera->voti);
            }

            return $carry->put($risultatoCircoscrizioneCamera->lista_id,
                $carry->get($risultatoCircoscrizioneCamera->lista_id) + $risultatoCircoscrizioneCamera->voti);
        }, collect());

        $totaliCamera = $risultatiCamera->sum();
        foreach ($risultatiCamera as $lista => $voti) {
            RisultatoCamera::unguard();
            RisultatoCamera::updateOrCreate([
                'lista_id' => $lista,
            ], [
                'voti' => $voti,
                'percentuale' => $voti > 0 ? round($voti / $totaliCamera * 100, 2) : 0,
            ]);
        }

        return Command::SUCCESS;
    }
}
