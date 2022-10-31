<?php

namespace App\Console\Commands;

use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\RisultatoCamera;
use App\Models\RisultatoCircoscrizioneCamera;
use App\Models\RisultatoCircoscrizioneSenato;
use App\Models\RisultatoCollegioPlurinominaleCamera;
use App\Models\RisultatoCollegioPlurinominaleSenato;
use App\Models\RisultatoCollegioUninominaleCamera;
use App\Models\RisultatoCollegioUninominaleCameraLista;
use App\Models\RisultatoCollegioUninominaleSenato;
use App\Models\RisultatoCollegioUninominaleSenatoLista;
use App\Models\RisultatoSenato;
use Illuminate\Console\Command;

class CacheRisultati extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:cache-risultati {--palazzo=}';

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
        if ($this->option('palazzo') == 'camera') {
            $palazzi = ['camera'];
        } elseif ($this->option('palazzo') == 'senato') {
            $palazzi = ['senato'];
        } else {
            $palazzi = ['camera', 'senato'];
        }

        foreach ($palazzi as $palazzo) {
            $this->info('Cacho risultati ' . $palazzo);

            if ($palazzo == 'camera') {
                $risultatoCollegioUninominaleModel = RisultatoCollegioUninominaleCamera::class;
                $risultatoCollegioUninominaleListaModel = RisultatoCollegioUninominaleCameraLista::class;
                $risultatoCollegioPlurinominaleModel = RisultatoCollegioPlurinominaleCamera::class;
                $risultatoCircoscrizioneModel = RisultatoCircoscrizioneCamera::class;
                $risultatoPalazzoModel = RisultatoCamera::class;
                $collegioUninominaleModel = CollegioUninominaleCamera::class;
                $collegioPlurinominaleModel = CollegioPlurinominaleCamera::class;
                $circoscrizioneModel = CircoscrizioneCamera::class;
                $circoscrizioniDaEscludere = [3];
            } else {
                $risultatoCollegioUninominaleModel = RisultatoCollegioUninominaleSenato::class;
                $risultatoCollegioUninominaleListaModel = RisultatoCollegioUninominaleSenatoLista::class;
                $risultatoCollegioPlurinominaleModel = RisultatoCollegioPlurinominaleSenato::class;
                $risultatoCircoscrizioneModel = RisultatoCircoscrizioneSenato::class;
                $risultatoPalazzoModel = RisultatoSenato::class;
                $collegioUninominaleModel = CollegioUninominaleSenato::class;
                $collegioPlurinominaleModel = CollegioPlurinominaleSenato::class;
                $circoscrizioneModel = CircoscrizioneSenato::class;
                $circoscrizioniDaEscludere = [2,4];
            }

            $risultatoCollegioUninominaleModel::truncate();
            $risultatoCollegioUninominaleListaModel::truncate();
            $risultatoCollegioPlurinominaleModel::truncate();
            $risultatoCircoscrizioneModel::truncate();
            $risultatoPalazzoModel::truncate();

            $collegioUninominaleModel::each(function ($collegioUninominale) use ($risultatoCollegioUninominaleModel, $risultatoCollegioUninominaleListaModel) {
                $voti = $collegioUninominale->candidature->mapWithKeys(function ($candidatura) {
                    return [
                        $candidatura->candidato->id => $candidatura->voti->sum('voti'),
                    ];
                });

                $sommaVoti = $voti->sum();

                $voti->each(function ($numeroVoti, $candidatoId) use ($sommaVoti, $collegioUninominale, $risultatoCollegioUninominaleModel) {
                    $risultatoCollegioUninominaleModel::unguard();
                    $risultatoCollegioUninominaleModel::updateOrCreate([
                        'collegio_id' => $collegioUninominale->id,
                        'candidato_id' => $candidatoId,
                    ], [
                        'voti' => $numeroVoti,
                        'percentuale' => $sommaVoti > 0 ? ((int) $numeroVoti / $sommaVoti * 100) : 0,
                    ]);
                });

                $sumPerCoalizione = $collegioUninominale->candidature->groupBy('coalizione_id')->map(function ($candidature) {
                    return $candidature->sum(function ($candidatura) {
                        return $candidatura->candidatureLista->sum(function ($candidaturaLista) {
                            return $candidaturaLista->voti->sum('voti');
                        });
                    });
                });

                $voti = collect();
                foreach ($collegioUninominale->candidature as $candidatura) {
                    $votiPerLista = collect();
                    $votiCandidato = $candidatura->voti->sum('voti_candidato');
                    $votiCoalizione = $sumPerCoalizione->get($candidatura->coalizione_id);
                    foreach ($candidatura->candidatureLista as $candidaturaLista) {
                        $votiLista = $candidaturaLista->voti->sum('voti');
                        $votiCalcolati = $votiCoalizione > 0 ? $votiLista + $votiCandidato * $votiLista / $votiCoalizione : 0;
                        $votiArrotondati = floor($votiCalcolati);
                        $resto = $votiCalcolati - $votiArrotondati;

                        // $this->info('Voti lista = ' . $votiArrotondati . ' (resto ' . $resto . ')');
                        $votiPerLista->put($candidaturaLista->lista->id, [
                            'voti' => $votiArrotondati,
                            'resto' => $resto,
                        ]);
                    }

                    $votiPerListaNetto = $votiPerLista->sum(function ($voto) {
                        return $voto['voti'];
                    });

                    $votiDaRiassegnare = $votiCandidato + $votiCoalizione - $votiPerListaNetto;

                    if ($votiDaRiassegnare > 0) {
                        $votiOrdinatiPerResto = $votiPerLista->sortByDesc(function ($voto) {
                            return $voto['resto'];
                        });
                        $listeDaRiassegnare = $votiOrdinatiPerResto->keys();
                        foreach (range(0, $votiDaRiassegnare - 1) as $i) {
                            $lista = $listeDaRiassegnare->get($i);

                            $old = $votiPerLista->get($lista);
                            $votiPerLista->put($lista, [
                                'voti' => $old['voti'] + 1,
                                'resto' => $old['resto'],
                            ]);
                        }
                    }

                    foreach ($votiPerLista as $listaId => $item) {
                        $voti->put($listaId, $item['voti']);
                    }
                }

                $sommaVoti = $voti->sum();

                foreach ($voti as $listaId => $numeroVoti) {
                    $risultatoCollegioUninominaleListaModel::unguard();
                    $risultatoCollegioUninominaleListaModel::updateOrCreate([
                        'collegio_id' => $collegioUninominale->id,
                        'lista_id' => $listaId,
                    ], [
                        'voti' => $numeroVoti,
                        'percentuale' => $sommaVoti > 0 ? ((int) $numeroVoti / $sommaVoti * 100) : 0,
                    ]);
                }
            });

            $collegioPlurinominaleModel::each(function ($collegioPlurinominale) use ($risultatoCollegioPlurinominaleModel) {
                $risultati = $collegioPlurinominale->collegiUninominali->reduce(function ($carry, $collegioUninominale) {
                    $risultatiCollegio = $collegioUninominale->risultatiListe;

                    foreach ($risultatiCollegio as $risultatoCollegio) {
                        if (!$carry->has($risultatoCollegio->lista_id)) {
                            $carry->put($risultatoCollegio->lista_id, 0);
                        }

                        $carry->put($risultatoCollegio->lista_id,
                            $carry->get($risultatoCollegio->lista_id) + $risultatoCollegio->voti);
                    }

                    return $carry;
                }, collect());

                $totaliPerCollegio = $risultati->sum();

                foreach ($risultati as $lista => $voti) {
                    $risultatoCollegioPlurinominaleModel::unguard();
                    $risultatoCollegioPlurinominaleModel::updateOrCreate([
                        'lista_id' => $lista,
                        'collegio_id' => $collegioPlurinominale->id,
                    ], [
                        'voti' => $voti,
                        'percentuale' => $voti > 0 ? round($voti / $totaliPerCollegio * 100, 2) : 0,
                    ]);
                }
            });

            $circoscrizioneModel::each(function ($circoscrizione) use ($risultatoCircoscrizioneModel) {
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
                    $risultatoCircoscrizioneModel::unguard();
                    $risultatoCircoscrizioneModel::updateOrCreate([
                        'lista_id' => $lista,
                        'circoscrizione_id' => $circoscrizione->id,
                    ], [
                        'voti' => $voti,
                        'percentuale' => $voti > 0 ? round($voti / $totaliPerCircoscrizione * 100, 2) : 0,
                    ]);
                }
            });

            $risultatiCamera = $risultatoCircoscrizioneModel::whereNotIn('circoscrizione_id', $circoscrizioniDaEscludere)->get()->reduce(function ($carry, $risultatoCircoscrizioneCamera) {
                if (!$carry->has($risultatoCircoscrizioneCamera->lista_id)) {
                    return $carry->put($risultatoCircoscrizioneCamera->lista_id, $risultatoCircoscrizioneCamera->voti);
                }

                return $carry->put($risultatoCircoscrizioneCamera->lista_id,
                    $carry->get($risultatoCircoscrizioneCamera->lista_id) + $risultatoCircoscrizioneCamera->voti);
            }, collect());

            $totaliCamera = $risultatiCamera->sum();
            foreach ($risultatiCamera as $lista => $voti) {
                $risultatoPalazzoModel::unguard();
                $risultatoPalazzoModel::updateOrCreate([
                    'lista_id' => $lista,
                ], [
                    'voti' => $voti,
                    'percentuale' => $voti > 0 ? round($voti / $totaliCamera * 100, 2) : 0,
                ]);
            }

            return Command::SUCCESS;
        }
    }
}
