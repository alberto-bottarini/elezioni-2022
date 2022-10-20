<?php

namespace App\Http\Controllers;

use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
use App\Models\Coalizione;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Comune;

class GeoController extends Controller
{
    public function circoscrizioniCamera()
    {
        $circoscrizioni = CircoscrizioneCamera::orderBy('nome')
            ->withCount('collegiPlurinominali')
            ->get();

        return view('geo.circoscrizioni_camera')
            ->with('circoscrizioni', $circoscrizioni);
    }

    public function circoscrizioneCamera(CircoscrizioneCamera $circoscrizione)
    {
        $candidatureUninominaliListe = collect();
        foreach ($circoscrizione->collegiPlurinominali as $collegioPlurinominale) {
            foreach ($collegioPlurinominale->collegiUninominali()->first()->candidature as $candidatura) {
                $candidatureUninominaliListe = $candidatureUninominaliListe->merge($candidatura->candidatureLista);
            }
        }

        $coalizioni = Coalizione::all()->keyBy('id');
        $risultatiNested = $this->getRisultatiNested(
            $circoscrizione->risultati,
            $candidatureUninominaliListe
        );

        return view('geo.circoscrizione_camera')
            ->with('circoscrizione', $circoscrizione)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiNested', $risultatiNested);
    }

    public function collegioPlurinominaleCamera(CollegioPlurinominaleCamera $collegioPlurinominale)
    {
        $candidatureUninominaliListe = collect();
        foreach ($collegioPlurinominale->collegiUninominali()->first()->candidature as $candidatura) {
            $candidatureUninominaliListe = $candidatureUninominaliListe->merge($candidatura->candidatureLista);
        }

        $coalizioni = Coalizione::all()->keyBy('id');
        $risultatiNested = $this->getRisultatiNested(
            $collegioPlurinominale->risultati,
            $candidatureUninominaliListe
        );

        return view('geo.collegio_plurinominale_camera')
            ->with('collegio', $collegioPlurinominale)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiNested', $risultatiNested);
    }

    public function collegioUninominaleCamera(CollegioUninominaleCamera $collegioUninominale)
    {
        return view('geo.collegio_uninominale_camera')
            ->with('collegio', $collegioUninominale);
    }

    public function circoscrizioniSenato()
    {
        $circoscrizioni = CircoscrizioneSenato::orderBy('nome')
            ->withCount('collegiPlurinominali')
            ->get();

        return view('geo.circoscrizioni_senato')
            ->with('circoscrizioni', $circoscrizioni);
    }

    public function circoscrizioneSenato(CircoscrizioneSenato $circoscrizione)
    {
        $collegiPlurinominali = $circoscrizione->collegiPlurinominali()
            ->orderBy('nome')
            ->withCount('collegiUninominali')
            ->get();

        return view('geo.circoscrizione_senato')
            ->with('circoscrizione', $circoscrizione)
            ->with('collegiPlurinominali', $collegiPlurinominali);
    }

    public function collegioPlurinominaleSenato(CollegioPlurinominaleSenato $collegioPlurinominale)
    {
        return view('geo.collegio_plurinominale_senato')
            ->with('collegio', $collegioPlurinominale);
    }

    public function collegioUninominaleSenato(CollegioUninominaleSenato $collegioUninominale)
    {
        return view('geo.collegio_uninominale_senato')
            ->with('collegio', $collegioUninominale);
    }

    public function comuni()
    {
        $comuni = Comune::orderBy('nome')->orderBy('provincia')->get();
        $comuniPerProvincia = $comuni->groupBy('provincia')->sortKeys();

        return view('geo.comuni')
            ->with('comuniPerProvincia', $comuniPerProvincia);
    }

    public function comune(Comune $comune)
    {
        $collegiUninominaliCamera = $comune->collegiUninominaliCamera;

        $collegiUninominaliCamera->load([
            'candidature',
            'candidature.risultati' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
            'candidature.candidatureLista',
            'candidature.candidatureLista.risultati' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
        ]);
        // dd($comune->collegiUninominaliCamera->first()->candidature->toArray());
        return view('geo.comune')
            ->with('comune', $comune);
    }

    private function getRisultatiNested($risultati, $candidatureUninominali)
    {
        return $risultati->reduce(function ($carry, $risultato) use ($candidatureUninominali) {
            $coalizioneId = $candidatureUninominali->firstWhere('lista_id', $risultato->lista_id)->candidatura->coalizione_id;
            if (!$carry->has($coalizioneId)) {
                $carry->put($coalizioneId, [
                    'voti' => 0,
                    'risultati' => [],
                ]);
            }

            $item = $carry->get($coalizioneId);
            $item['voti'] += $risultato->voti;
            $item['risultati'][] = $risultato;

            usort($item['risultati'], function ($a, $b) {
                return $b->voti <=> $a->voti;
            });

            $carry->put($coalizioneId, $item);

            return $carry;
        }, collect())->map(function ($item) use($risultati) {
            $sum = $risultati->sum('voti');
            $item['percentuale'] = round($item['voti'] / $sum * 100, 2);

            return $item;
        })->sortByDesc('voti');
    }
}
