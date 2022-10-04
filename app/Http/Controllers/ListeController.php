<?php

namespace App\Http\Controllers;

use App\Models\Lista;

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
        return view('liste.lista')
            ->with('lista', $lista);
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
}
