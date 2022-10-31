<?php

namespace App\Http\Controllers;

use App\Models\Coalizione;
use App\Models\Lista;
use App\Models\RisultatoCamera;
use App\Models\RisultatoCircoscrizioneCamera;
use App\Models\RisultatoCircoscrizioneSenato;
use App\Models\RisultatoSenato;

class ListeController extends Controller
{
    public function liste()
    {
        $liste = Lista::orderBy('nome')->get();

        return view('liste.liste')
            ->with('liste', $liste);
    }

    public function lista(Lista $lista)
    {
        $risultatoCamera = RisultatoCamera::where('lista_id', $lista->id)->first();
        $risultatoSenato = RisultatoSenato::where('lista_id', $lista->id)->first();

        $risultatiCircoscrizioniCamera = RisultatoCircoscrizioneCamera::where('lista_id', $lista->id)->with('circoscrizione')->get();
        $risultatiCircoscrizioniSenato = RisultatoCircoscrizioneSenato::where('lista_id', $lista->id)->with('circoscrizione')->get();

        return view('liste.lista')
            ->with('lista', $lista)
            ->with('risultatoCamera', $risultatoCamera)
            ->with('risultatoSenato', $risultatoSenato)
            ->with('risultatiCircoscrizioniCamera', $risultatiCircoscrizioniCamera)
            ->with('risultatiCircoscrizioniSenato', $risultatiCircoscrizioniSenato);
    }

    public function listaPlurinominaliCamera(Lista $lista)
    {
        return view('liste.candidature_plurinominali_camera')
            ->with('lista', $lista);
    }

    public function listaUninominaliCamera(Lista $lista)
    {
        return view('liste.candidature_uninominali_camera')
            ->with('lista', $lista);
    }

    public function listaPlurinominaliSenato(Lista $lista)
    {
        return view('liste.candidature_plurinominali_senato')
            ->with('lista', $lista);
    }

    public function listaUninominaliSenato(Lista $lista)
    {
        return view('liste.candidature_uninominali_senato')
            ->with('lista', $lista);
    }

    public function coalizioni()
    {
        $coalizioni = Coalizione::orderBy('nome')->get();

        return view('liste.coalizioni')
            ->with('coalizioni', $coalizioni);
    }

    public function coalizione(Coalizione $coalizione)
    {
        return view('liste.coalizione')
            ->with('coalizione', $coalizione);
    }

    public function coalizioneUninominaliCamera(Coalizione $coalizione)
    {
        return view('liste.candidature_coalizioni_uninominali_camera')
            ->with('coalizione', $coalizione);
    }

    public function coalizioneUninominaliSenato(Coalizione $coalizione)
    {
        return view('liste.candidature_coalizioni_uninominali_senato')
            ->with('coalizione', $coalizione);
    }
}
