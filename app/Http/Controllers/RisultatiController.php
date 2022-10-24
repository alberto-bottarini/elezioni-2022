<?php

namespace App\Http\Controllers;

use App\Models\CircoscrizioneCamera;

class RisultatiController extends Controller
{
    public function camera()
    {

        $risultati = CircoscrizioneCamera::where('id', '<>', 3)->reduce(function($carry, $circoscrizione) {
            $carry->merge($circoscrizione->risultati);
            return $carry;
        }, collect());


        // return view('risultati.camera')
        //     ->with('risultati', $risultati);
    }
}
