@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $collegio->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' => 'Scopri grazie ad Elezioniamo le candidature e i risultati in ' . $collegio->nome,
    ])
@endsection

@section('content')
    @include('partials.breadcrumb', [
        'crumbs' => [
            ['route' => route('home'), 'label' => 'Home'],
            ['route' => route('circoscrizioni_camera'), 'label' => 'Circoscrizioni Camera'],
            [
                'route' => route('circoscrizione_camera', $collegio->circoscrizione),
                'label' => $collegio->circoscrizione->nome,
            ],
        ],
        'title' => $collegio->nome,
    ])

    <h2 class="section">Collegi uninominali</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach ($collegio->collegiUninominali as $collegioUninominale)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_uninominale_camera', $collegioUninominale) }}"
                        class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegioUninominale->nome }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Risultati</h2>
    @include('partials.geo.risultati_plurinominale')

    <h2 class="section">Eletti</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Lista</th>
        </tr>
        @foreach ($candidature as $candidatura)
            @foreach ($candidatura->candidati as $candidato)
                @if ($candidato->pivot->eletto)
                    <tr class="tr-standard">
                        <td>
                            <a href="{{ route('candidato', $candidato) }}" class="anchor">
                                @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidato->nome }}
                            </a>
                        </td>
                        <td>
                            <a class="anchor" href="{{ route('lista', $candidatura->lista) }}">
                                @svg('heroicon-o-list-bullet', 'w-4 h-4 inline-block mr-2'){{ $candidatura->lista->nome }}
                            </a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </table>

    @if ($collegio->candidature->count())
        <h2 class="section">Candidati</h2>
        @include('partials.geo.candidati_plurinominale', ['candidature' => $candidature])
    @endif
@endsection
