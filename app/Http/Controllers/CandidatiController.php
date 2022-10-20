<?php

namespace App\Http\Controllers;

use App\Models\Candidato;

class CandidatiController extends Controller
{
    public function candidati()
    {
        $candidati = Candidato::orderBy('nome')
            ->get();

        return view('candidati.candidati')
            ->with('candidati', $candidati);
    }

    public function candidato(Candidato $candidato)
    {
        return view('candidati.candidato')
            ->with('candidato', $candidato);
    }
}
