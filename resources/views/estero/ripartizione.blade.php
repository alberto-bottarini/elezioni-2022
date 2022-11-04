@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $ripartizione->nome .' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati nella ripartizione ' . $ripartizione->nome
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('ripartizioni_estero'), 'label' => 'Ripartizioni estero' ],
            
        ],
        'title' => $ripartizione->nome
    ])
    
    <h2 class="section">Candidature Camera</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Candidato</th>
            <th>Lista</th>
        </tr>
        @foreach($ripartizione->candidatureCamera as $candidatura)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">
                        @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a>
                </td>
                <td>
                    <a href="{{ route('lista', $candidatura->lista) }}" class="anchor">
                        @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block') {{ $candidatura->lista->nome }}</a>
                </td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Candidature Senato</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Candidato</th>
            <th>Lista</th>
        </tr>
        @foreach($ripartizione->candidatureSenato as $candidatura)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">
                        @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a>
                </td>
                <td>
                    <a href="{{ route('lista', $candidatura->lista) }}" class="anchor">
                        @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block') {{ $candidatura->lista->nome }}</a>
                </td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Nazioni</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>
                Nome
            </th>
        </tr>
        @foreach($ripartizione->nazioni as $nazione)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('nazione_estero', $nazione) }}" class="anchor">
                        @svg('heroicon-o-flag', 'w-5 h-5 inline-block') {{ $nazione->nome }}</a>
                </td>
            </tr>
        @endforeach
    </table>
        
@endsection