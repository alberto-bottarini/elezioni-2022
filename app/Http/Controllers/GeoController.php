<?php

namespace App\Http\Controllers;

use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;

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
        $collegiPlurinominali = $circoscrizione->collegiPlurinominali()
            ->orderBy('nome')
            ->withCount('collegiUninominali')
            ->get();

        return view('geo.circoscrizione_camera')
            ->with('circoscrizione', $circoscrizione)
            ->with('collegiPlurinominali', $collegiPlurinominali);
    }

    public function collegioPlurinominaleCamera(CollegioPlurinominaleCamera $collegioPlurinominale)
    {
        $collegiUninominali = $collegioPlurinominale->collegiUninominali()
            ->orderBy('nome')
            ->get();

        $candidature = $collegioPlurinominale->candidature;

        return view('geo.collegio_plurinominale_camera')
            ->with('collegio', $collegioPlurinominale)
            ->with('collegiUninominali', $collegiUninominali)
            ->with('candidature', $candidature);
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
        $collegiUninominali = $collegioPlurinominale->collegiUninominali()
            ->orderBy('nome')
            ->get();

        $candidature = $collegioPlurinominale->candidature()
            ->orderBy('numero')
            ->get();

        return view('geo.collegio_plurinominale_senato')
            ->with('collegio', $collegioPlurinominale)
            ->with('collegiUninominali', $collegiUninominali)
            ->with('candidature', $candidature);
    }

    public function collegioUninominaleSenato(CollegioUninominaleSenato $collegioUninominale)
    {
        $collegioUninominale->candidature;

        return view('geo.collegio_uninominale_senato')
            ->with('collegio', $collegioUninominale);
    }

}
