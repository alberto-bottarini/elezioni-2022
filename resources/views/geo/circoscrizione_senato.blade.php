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
            [ 'route' => route('circoscrizioni_senato'), 'label' => 'Circoscrizioni Senato' ],
        ],
        'title' => $circoscrizione->nome
    ])

    <h2 class="section">Collegi plurinominali</h2>
    
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Numero collegi uninominali</th>
        </tr>
        @foreach($collegiPlurinominali as $collegio)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_plurinominale_senato', $collegio) }}" class="anchor">@svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $collegio->nome }}</a></td>
                <td>{{ $collegio->collegi_uninominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection