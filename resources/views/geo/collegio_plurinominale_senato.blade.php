@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $collegio->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati in ' . $collegio->nome
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_senato'), 'label' => 'Circoscrizioni Senato' ],
            [ 'route' => route('circoscrizione_senato', $collegio->circoscrizione), 'label' => $collegio->circoscrizione->nome ],
        ],
        'title' => $collegio->nome
    ])

    <h2 class="section">Collegi uninominali</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($collegio->collegiUninominali as $collegioUninominale)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_uninominale_senato', $collegioUninominale) }}" class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegioUninominale->nome }}</a></td>
            </tr>
        @endforeach
    </table>
    
    <h2 class="section">Risultati</h2>
    @include('partials.geo.risultati_plurinominale')

    @if($collegio->candidature->count())
        <h2 class="section">Candidati</h2>
        @include('partials.geo.candidati_plurinominale', ['candidature' => $candidature ])
    @endif

@endsection