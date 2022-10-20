<?php

namespace App\Console\Commands;

use App\Models\CircoscrizioneCamera;
use App\Models\CollegioPlurinominaleCamera;
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
        CollegioPlurinominaleCamera::each(function ($collegioPlurinominale) {
            $risultati = $collegioPlurinominale->collegiUninominali->reduce(function ($carry, $collegioUninominale) {
                foreach ($collegioUninominale->candidature as $candidatura) {
                    $votiCandidato = $candidatura->risultati->sum('voti_candidato');
                    $sum = 0;
                    foreach ($candidatura->candidatureLista as $candidaturaLista) {
                        $sum += $candidaturaLista->risultati->sum('voti');
                    }
                    foreach ($candidatura->candidatureLista as $candidaturaLista) {
                        $lista = $candidaturaLista->lista;
                        $voti = $candidaturaLista->risultati->sum('voti');
                        $votiTotali = $sum > 0 ? $voti + ($votiCandidato * $voti / $sum) : 0;

                        if (!$carry->has($lista->id)) {
                            $carry->put($lista->id, $votiTotali);
                        } else {
                            $carry->put($lista->id, $carry->get($lista->id) + $votiTotali);
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
                    'percentuale' => $voti > 0 ? round($voti / $totaliPerCollegio * 100, 2) : 0
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
                    'percentuale' => $voti > 0 ? round($voti / $totaliPerCircoscrizione * 100, 2) : 0
                ]);
            }
        });

        return Command::SUCCESS;
    }
}
