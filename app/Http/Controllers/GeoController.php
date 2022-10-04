<?php

namespace App\Http\Controllers;

use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
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

        return view('geo.collegio_plurinominale_camera')
            ->with('collegio', $collegioPlurinominale)
            ->with('collegiUninominali', $collegiUninominali);
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
        
        return view('geo.comune')
            ->with('comune', $comune);
    }

}
