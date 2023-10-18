<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Comune;
use App\Models\Lista;
use App\Models\NazioneEstero;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\List_;

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
            ->get()
            ->map(function($candidato) {
                return [
                    'label' => $candidato->nome,
                    'route' => route('candidato', $candidato),
                    'type' => 'candidato'
                ];
            })
            ->toArray()
        );

        $results = array_merge($results, Lista::whereLike('nome', $search)
            ->get()
            ->map(function($lista) {
                return [
                    'label' => $lista->nome,
                    'route' => route('lista', $lista),
                    'type' => 'lista'
                ];
            })
            ->toArray()
        );


        $results = array_merge($results, NazioneEstero::whereLike('nome', $search)
            ->get()
            ->map(function($nazione) {
                return [
                    'label' => $nazione->nome,
                    'route' => route('nazione_estero', $nazione),
                    'type' => 'nazione'
                ];
            })
            ->toArray()
        );


        usort($results, function($a, $b) {
            return $a['label'] <=> $b['label'];
        });

        return view('partials.search')
            ->with('results', $results);
    }
}
