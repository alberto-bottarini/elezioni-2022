@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $comune->nomeCompleto . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati nel comune di ' . $comune->nomeCompleto,
        'og' => asset('og/comune/' . $comune->id . '.png'),
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('comuni'), 'label' => 'Comuni' ],
        ],
        'title' => $comune->nome . ' (' . $comune->provincia.')'
    ])

    <h2 class="section">Collegi</h2>

    <table class="table">
        <tr class="tr-standard">
            <td class="align-top">
                Collegio uninominale Camera
            </td>
            <td>
                @foreach($comune->collegiUninominaliCamera as $collegio)
                    <div>
                        <a class="anchor" href="{{ route('collegio_uninominale_camera', ['collegioUninominale' => $collegio ]) }}">
                            @svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegio->nome }}
                        </a>
                    </div>
                @endforeach
            </td>
        </tr>
        <tr class="tr-standard">
            <td class="align-top">
                Collegio plurinominale Camera
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_plurinominale_camera', ['collegioPlurinominale' => $comune->collegiUninominaliCamera->first()->collegioPlurinominale ]) }}">
                    @svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $comune->collegiUninominaliCamera->first()->collegioPlurinominale->nome }}
                </a>
            </td>
        </tr>
        <tr class="tr-standard">
            <td class="align-top">
                Collegio uninominale Senato
            </td>
            <td>
                @foreach($comune->collegiUninominaliSenato as $collegio)
                    <div>
                        <a class="anchor" href="{{ route('collegio_uninominale_senato', ['collegioUninominale' => $collegio ]) }}">
                            @svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegio->nome }}
                        </a>
                    </div>
                @endforeach
            </td>
        </tr>
        <tr class="tr-standard">
            <td class="align-top">
                Collegio plurinominale Senato
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_plurinominale_senato', ['collegioPlurinominale' => $comune->collegiUninominaliSenato->first()->collegioPlurinominale ]) }}">
                    @svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $comune->collegiUninominaliSenato->first()->collegioPlurinominale->nome }}
                </a>
            </td>
        </tr>
    </table>

    <h2 class="section">Elettori e affluenza</h2>

    <table class="table">
        <tr class="tr-standard">
            <td class="align-top">
                Elettori
            </td>
            <td>
                <b>{{ number_format($comune->affluenza->elettori, 0, 2, '.') }}</b>
            </td>
        </tr>

        <tr class="tr-standard">
            <td class="align-top">
                Votanti
            </td>
            <td>
                <b>{{ number_format($comune->affluenza->voti, 0, 2, '.') }}</b>
            </td>
        </tr>

        <tr class="tr-standard">
            <td class="align-top">
                Affluenza totale
            </td>
            <td>
                <div class="bg-sky-200 relative h-8">
                    <div class="bg-sky-400 absolute h-8 p-2 text-white font-bold text-center" style="width: {{ $comune->affluenza->voti / $comune->affluenza->elettori *  100 }}%;"">{{ round($comune->affluenza->voti / $comune->affluenza->elettori * 100, 2) }}%</div> 
                </div>
            </td>
        </tr>
    </table>

    @foreach($comune->collegiUninominaliCamera as $collegio)
        <h2 class="section">Risultati Camera {{ $collegio->nome }}</h2>

        <table class="table">
            <tr class="tr-heading">
                <th>Nome Candidato</th>
                <th>Voti totali</th>
                <th>Voti al candidato</th>
                <th>Voti alle liste</th>
            </tr>
            @foreach($collegio->candidature->sortByDesc(function($candidatura) {
                return $candidatura->risultati->first()->voti;
            }) as $candidatura)
                <tr class="tr-standard">
                    <td><a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a></td>
                    <td>{{ format_voti($candidatura->risultati->first()->voti) }}</td>
                    <td>{{ format_voti($candidatura->risultati->first()->voti_candidato) }}</td>
                    <td class="w-1/2 px-0">
                        <table class="table table-small">
                            <tr class="tr-heading">
                                <th class="w-5/6">Lista</th>
                                <th class="w-1/6">Voti</th>
                            </tr>
                            @foreach($candidatura->candidatureLista->sortByDesc(function($lista) {
                                return $lista->risultati->first()->voti;
                            }) as $candidaturaLista)
                                <tr class="tr-standard">
                                    <td class="w-5/6"><a href="{{ route('lista', $candidaturaLista->lista) }}" class="anchor">
                                        @svg('heroicon-o-list-bullet', 'w-3 h-3 inline-block mr-2'){{ $candidaturaLista->lista->nome }}</a></td>
                                    <td class="w-1/6">{{ format_voti($candidaturaLista->risultati->first()->voti) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    @endforeach

    @foreach($comune->collegiUninominaliCamera as $collegio)
        <h2 class="section">Candidati Uninominale Camera {{ $collegio->nome }}</h2>

        @include('partials.geo.candidati_uninominale', ['candidature' => $collegio->candidature ])
    @endforeach

    <h2 class="section">Candidati Plurinominale Camera {{ $comune->collegiUninominaliCamera->first()->collegioPlurinominale->nome }}</h2>

    @include('partials.geo.candidati_plurinominale', ['candidature' => $comune->collegiUninominaliCamera->first()->collegioPlurinominale->candidature ])


    {{-- @foreach($comune->collegiUninominaliSenato as $collegio)
        <h2 class="section">Candidati Uninominale Senato {{ $collegio->nome }}</h2>

        @include('partials.geo.candidati_uninominale', ['candidature' => $collegio->candidature ])
    @endforeach

    <h2 class="section">Candidati Plurinominale Senato {{ $comune->collegiUninominaliSenato->first()->collegioPlurinominale->nome }}</h2>

    @include('partials.geo.candidati_plurinominale', ['candidature' => $comune->collegiUninominaliSenato->first()->collegioPlurinominale->candidature ]) --}}

@endsection