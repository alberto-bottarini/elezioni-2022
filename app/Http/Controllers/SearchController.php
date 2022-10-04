<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Comune;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;

        if(str_contains($search, '%')) {
            return;
        }

        if(strlen($search) < 3) {
            return;
        }

        $results = [];
        
        $results = array_merge($results, Comune::whereLike('nome', $search)
            ->get()
            ->map(function($comune) {
                return [
                    'label' => $comune->nome,
                    'route' => route('comune', $comune),
                    'type' => 'comune'
                ];
            })
            ->toArray()
        );

        $results = array_merge($results, Candidato::whereLike('nome', $search)
            ->orWhereLike('cognome', $search)
            ->get()
            ->map(function($candidato) {
                return [
                    'label' => $candidato->nome . ' ' . $candidato->cognome,
                    'route' => 'http://www.gazzetta.it',
                    'type' => 'candidato'
                ];
            })
            ->toArray()
        );

        return view('partials.search')
            ->with('results', $results);
    }
}
