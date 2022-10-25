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
use App\Models\RisultatoCamera;
use App\Models\RisultatoSenato;
use DivisionByZeroError;

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
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($circoscrizione->risultati);

        return view('geo.circoscrizione_camera')
            ->with('circoscrizione', $circoscrizione)
            ->with('coalizioni', Coalizione::all())
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione);
    }

    public function collegioPlurinominaleCamera(CollegioPlurinominaleCamera $collegioPlurinominale)
    {
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($collegioPlurinominale->risultati);

        return view('geo.collegio_plurinominale_camera')
            ->with('collegio', $collegioPlurinominale)
            ->with('coalizioni', Coalizione::all())
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione);
    }

    public function collegioUninominaleCamera(CollegioUninominaleCamera $collegioUninominale)
    {
        $risultatiPerCandidato = $collegioUninominale->risultati->keyBy('candidato_id');
        $risultatiPerLista = $collegioUninominale->risultatiListe->keyBy('lista_id');

        $candidature = $collegioUninominale->candidature->sortByDesc(function ($candidatura) use ($risultatiPerCandidato) {
            return $risultatiPerCandidato->get($candidatura->candidato_id)->voti;
        })->map(function ($candidatura) use ($risultatiPerLista) {
            $candidatura->candidatureLista = $candidatura->candidatureLista->sortByDesc(function ($candidaturaLista) use ($risultatiPerLista) {
                return $risultatiPerLista->get($candidaturaLista->lista_id)->voti;
            });

            return $candidatura;
        });

        return view('geo.collegio_uninominale_camera')
            ->with('collegio', $collegioUninominale)
            ->with('candidature', $candidature)
            ->with('risultatiPerCandidato', $risultatiPerCandidato)
            ->with('risultatiPerLista', $risultatiPerLista);
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
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($circoscrizione->risultati);

        return view('geo.circoscrizione_senato')
            ->with('circoscrizione', $circoscrizione)
            ->with('coalizioni', Coalizione::all())
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione);
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
            'candidature.voti' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
            'candidature.candidatureLista',
            'candidature.candidatureLista.voti' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
        ]);

        $collegiUninominaliSenato = $comune->collegiUninominaliSenato;

        $collegiUninominaliSenato->load([
            'candidature',
            'candidature.voti' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
            'candidature.candidatureLista',
            'candidature.candidatureLista.voti' => function ($query) use ($comune) {
                $query->where('comune_id', $comune->id);
            },
        ]);

        return view('geo.comune')
            ->with('comune', $comune);
    }

    public function camera()
    {
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione(RisultatoCamera::all());
        $risultatiVDA = $this->groupRisultatiByCoalizione(CircoscrizioneCamera::where('id', 3)->first()->risultati);

        return view('geo.camera')
            ->with('coalizioni', Coalizione::all())
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('risultatiVDA', $risultatiVDA);
    }

    public function senato()
    {
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione(RisultatoSenato::all());
        $risultatiVDA = $this->groupRisultatiByCoalizione(CircoscrizioneSenato::where('id', 2)->first()->risultati);
        $risultatiTAA = $this->groupRisultatiByCoalizione(CircoscrizioneSenato::where('id', 4)->first()->risultati);

        return view('geo.senato')
            ->with('coalizioni', Coalizione::all())
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('risultatiVDA', $risultatiVDA)
            ->with('risultatiTAA', $risultatiTAA);
    }

    private function groupRisultatiByCoalizione($risultati)
    {
        $risultatiPerLista = $risultati->groupBy('lista_id');

        $risultatiPerCoalizione = Coalizione::all()->reduce(function ($carry, $coalizione) use ($risultatiPerLista) {
            $risultatiListe = $coalizione->liste->reduce(function ($carry, $lista) use ($risultatiPerLista) {
                $risultati = $risultatiPerLista->get($lista->id);
                if ($risultati && $risultati->count() > 0) {
                    return $carry->merge($risultati);
                }

                return $carry;
            }, collect());

            if ($risultatiListe->count()) {
                $carry->put($coalizione->id, [
                    'risultati' => $risultatiListe->sortByDesc('voti'),
                    'voti' => $risultatiListe->sum('voti'),
                ]);
            }

            return $carry;
        }, collect());

        return $risultatiPerCoalizione->map(function ($coalizioneItem) use ($risultatiPerCoalizione) {
            try {
                $coalizioneItem['percentuale'] = $coalizioneItem['voti'] / $risultatiPerCoalizione->sum(fn ($item) => $item['voti']) * 100;
            } catch (DivisionByZeroError) {
                $coalizioneItem['percentuale'] = 0;
            }

            return $coalizioneItem;
        })->sortByDesc(function ($coalizioneItem) {
            return $coalizioneItem['percentuale'];
        });
    }
}
