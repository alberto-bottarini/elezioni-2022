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
            [ 'route' => route('circoscrizioni_camera'), 'label' => 'Circoscrizioni Camera' ],
            [ 'route' => route('circoscrizione_camera', $collegio->collegioPlurinominale->circoscrizione), 'label' => $collegio->collegioPlurinominale->circoscrizione->nome ],
            [ 'route' => route('collegio_plurinominale_camera', $collegio->collegioPlurinominale), 'label' => $collegio->collegioPlurinominale->nome ],
            
        ],
        'title' => $collegio->nome
    ])

    <h2 class="section">Candidati</h2>

    @include('partials.geo.candidati_uninominale', ['candidature' => $collegio->candidature ])

    <h2 class="section">Comuni</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($collegio->comuni as $comune)
            <tr class="tr-standard">
                <td><a href="{{ route('comune', $comune) }}" class="anchor">@svg('heroicon-o-map-pin', 'w-5 h-5 inline-block') {{ $comune->nome }}</td>
            </tr>
        @endforeach
    </table>


@endsection