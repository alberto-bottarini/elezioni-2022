@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $coalizione->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati della coalizione ' . $coalizione->nome
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('coalizioni'), 'label' => 'Coalizioni' ]
        ],
        'title' => $coalizione->nome
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Tipologia</th>
            <th>Numero candidature</th>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('coalizione_collegi_uninominali_camera', $coalizione) }}">Uninominale Camera</a></td>
            <td>{{ $coalizione->candidatureCollegiUninominaliCamera()->count() }}</td>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('coalizione_collegi_uninominali_senato', $coalizione) }}">Uninominale Senato</a></td>
            <td>{{ $coalizione->candidatureCollegiUninominaliSenato()->count() }}</td>
        </tr>
    </table>


@endsection