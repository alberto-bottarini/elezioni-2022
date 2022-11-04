<?php

namespace App\Http\Controllers;

use App\Models\NazioneEstero;
use App\Models\RipartizioneEstero;
use Illuminate\Http\Request;

class EsteroController extends Controller
{
    public function ripartizioni()
    {
        $ripartizioni = RipartizioneEstero::orderBy('nome')
            ->get();

        return view('estero.ripartizioni')
            ->with('ripartizioni', $ripartizioni);
    }

    public function ripartizione(RipartizioneEstero $ripartizioneEstero)
    {
        $ripartizioneEstero->load([
            'candidatureCamera.candidato',
            'candidatureCamera.lista',
            'candidatureSenato.candidato',
            'candidatureSenato.lista'
        ]);
        return view('estero.ripartizione')
            ->with('ripartizione', $ripartizioneEstero);
    }

    public function nazione(NazioneEstero $nazioneEstero)
    {
        $nazioneEstero->load([
            'preferenzeCamera.candidatura.candidato',
            'preferenzeCamera.candidatura.lista',
            'preferenzeSenato.candidatura.candidato',
            'preferenzeSenato.candidatura.lista',
            'votiCamera.lista',
            'votiSenato.lista',
        ]);

        $preferenzeCameraPerLista = $nazioneEstero->preferenzeCamera->sortByDesc('preferenze')->groupBy(function ($preferenza) {
            return $preferenza->candidatura->lista->id;
        });

        $preferenzeSenatoPerLista = $nazioneEstero->preferenzeSenato->sortByDesc('preferenze')->groupBy(function ($preferenza) {
            return $preferenza->candidatura->lista->id;
        });

        return view('estero.nazione')
            ->with('nazione', $nazioneEstero)
            ->with('preferenzeCameraPerLista', $preferenzeCameraPerLista)
            ->with('preferenzeSenatoPerLista', $preferenzeSenatoPerLista);
    }
}
