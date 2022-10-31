@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $circoscrizione->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati in ' . $circoscrizione->nome
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_camera'), 'label' => 'Circoscrizioni Camera' ],
        ],
        'title' => $circoscrizione->nome
    ])

    <h2 class="section">Collegi plurinominali</h2>
    
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($circoscrizione->collegiPlurinominali as $collegio)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_plurinominale_camera', $collegio) }}" class="anchor">@svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $collegio->nome }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Risultati</h2>
    @include('partials.geo.risultati_plurinominale')
    
@endsection