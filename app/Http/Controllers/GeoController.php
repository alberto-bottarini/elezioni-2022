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
use App\Models\RisultatoCameraEstero;
use App\Models\RisultatoSenato;
use App\Models\RisultatoSenatoEstero;
use DivisionByZeroError;

class GeoController extends Controller
{
    public function circoscrizioniCamera()
    {
        $circoscrizioni = CircoscrizioneCamera::orderBy('nome')
            ->get();

        return view('geo.circoscrizioni_camera')
            ->with('circoscrizioni', $circoscrizioni);
    }

    public function circoscrizioneCamera(CircoscrizioneCamera $circoscrizione)
    {
        $coalizioni = Coalizione::with('liste')->get();
        $risultati = $circoscrizione->risultati->load('lista');
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);

        return view('geo.circoscrizione_camera')
            ->with('circoscrizione', $circoscrizione)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione);
    }

    public function collegioPlurinominaleCamera(CollegioPlurinominaleCamera $collegioPlurinominale)
    {
        $coalizioni = Coalizione::with('liste')->get();
        $risultati = $collegioPlurinominale->risultati->load('lista');
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);
        $candidature = $collegioPlurinominale->candidature->load('candidati')->load('lista');

        return view('geo.collegio_plurinominale_camera')
            ->with('collegio', $collegioPlurinominale)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('candidature', $candidature);
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
            ->get();

        return view('geo.circoscrizioni_senato')
            ->with('circoscrizioni', $circoscrizioni);
    }

    public function circoscrizioneSenato(CircoscrizioneSenato $circoscrizione)
    {
        $coalizioni = Coalizione::with('liste')->get();
        $risultati = $circoscrizione->risultati->load('lista');
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);

        return view('geo.circoscrizione_senato')
            ->with('circoscrizione', $circoscrizione)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione);
    }

    public function collegioPlurinominaleSenato(CollegioPlurinominaleSenato $collegioPlurinominale)
    {
        $coalizioni = Coalizione::with('liste')->get();
        $risultati = $collegioPlurinominale->risultati->load('lista');
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);
        $candidature = $collegioPlurinominale->candidature->load('candidati')->load('lista');

        return view('geo.collegio_plurinominale_senato')
            ->with('collegio', $collegioPlurinominale)
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('candidature', $candidature);
    }

    public function collegioUninominaleSenato(CollegioUninominaleSenato $collegioUninominale)
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

        return view('geo.collegio_uninominale_senato')
            ->with('collegio', $collegioUninominale)
            ->with('candidature', $candidature)
            ->with('risultatiPerCandidato', $risultatiPerCandidato)
            ->with('risultatiPerLista', $risultatiPerLista);
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

        //dd($collegiUninominaliSenato->first()->candidature->first()->voti->toArray());

        return view('geo.comune')
            ->with('comune', $comune);
    }

    public function camera()
    {
        $risultati = RisultatoCamera::with('lista')->get();
        $coalizioni = Coalizione::with('liste')->get();
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);
        $risultatiVDA = $this->groupRisultatiByCoalizione(CircoscrizioneCamera::where('id', 3)->first()->risultati, $coalizioni);
        $risultatiEstero = RisultatoCameraEstero::with('lista')->get();
        $risultatiEsteroPerCoalizione = $this->groupRisultatiByCoalizione($risultatiEstero, $coalizioni);

        return view('geo.camera')
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('risultatiEsteroPerCoalizione', $risultatiEsteroPerCoalizione)
            ->with('risultatiVDA', $risultatiVDA);
    }

    public function senato()
    {
        $risultati = RisultatoSenato::with('lista')->get();
        $coalizioni = Coalizione::with('liste')->get();
        $risultatiPerCoalizione = $this->groupRisultatiByCoalizione($risultati, $coalizioni);
        $risultatiVDA = $this->groupRisultatiByCoalizione(CircoscrizioneSenato::where('id', 2)->first()->risultati, $coalizioni);
        $risultatiTAA = $this->groupRisultatiByCoalizione(CircoscrizioneSenato::where('id', 4)->first()->risultati, $coalizioni);
        $risultatiEstero = RisultatoSenatoEstero::with('lista')->get();
        $risultatiEsteroPerCoalizione = $this->groupRisultatiByCoalizione($risultatiEstero, $coalizioni);

        return view('geo.senato')
            ->with('coalizioni', $coalizioni)
            ->with('risultatiPerCoalizione', $risultatiPerCoalizione)
            ->with('risultatiEsteroPerCoalizione', $risultatiEsteroPerCoalizione)
            ->with('risultatiVDA', $risultatiVDA)
            ->with('risultatiTAA', $risultatiTAA);
    }

    private function groupRisultatiByCoalizione($risultati, $coalizioni)
    {
        $risultatiPerLista = $risultati->groupBy('lista_id');

        $risultatiPerCoalizione = $coalizioni->reduce(function ($carry, $coalizione) use ($risultatiPerLista) {
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
