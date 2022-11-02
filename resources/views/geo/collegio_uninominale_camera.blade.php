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

    <h2 class="section">Risultati</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome Candidato</th>
            <th>Voti totali</th>
            <th>Percentuale</th>
            <th>Voti alle liste</th>
        </tr>
        @foreach($candidature as $candidatura)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">
                        @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}
                        @if($candidatura->eletto) @svg('heroicon-o-star', 'w-5 h-5 inline-block') @endif 
                    </a>
                </td>
                <td>{{ format_voti($risultatiPerCandidato->get($candidatura->candidato_id)->voti) }}</td>
                <td>{{ format_percentuali($risultatiPerCandidato->get($candidatura->candidato_id)->percentuale) }}</td>
                <td class="w-1/2 px-0">
                    <table class="table table-small">
                        <tr class="tr-heading">
                            <th class="w-4/6">Lista</th>
                            <th class="w-1/6">Voti</th>
                            <th class="w-1/6">Percentuale</th>
                        </tr>
                        @foreach($candidatura->candidatureLista as $candidaturaLista)
                            <tr class="tr-standard">
                                <td class="w-4/6"><a href="{{ route('lista', $candidaturaLista->lista) }}" class="anchor">
                                    @svg('heroicon-o-list-bullet', 'w-3 h-3 inline-block mr-2'){{ $candidaturaLista->lista->nome }}</a></td>
                                <td class="w-1/6">{{ format_voti($risultatiPerLista->get($candidaturaLista->lista_id)->voti )}}</td>
                                <td class="w-1/6">{{ format_percentuali($risultatiPerLista->get($candidaturaLista->lista_id)->percentuale )}}</td>
                            </tr>
                        @endforeach

                    </table>
                </td>
            </tr>
        @endforeach
    </table>
    
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