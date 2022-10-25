@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Risultati Senato | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati alla Camera'
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            
        ],
        'title' => 'Risultati Senato'
    ])


    <h2 class="section">Risultati (esclusa Val d'Aosta e Trentino Alto Adige)</h2>
    @include('partials.geo.risultati_plurinominale')

    <h2 class="section">Risultati Val d'Aosta</h2>
    @include('partials.geo.risultati_plurinominale', ['risultatiPerCoalizione' => $risultatiVDA])

    <h2 class="section">Risultati Trentino Alto Adige</h2>
    @include('partials.geo.risultati_plurinominale', ['risultatiPerCoalizione' => $risultatiTAA])
    
@endsection