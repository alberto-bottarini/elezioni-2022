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
                ({{ number_format($comune->affluenza->elettori_m, 0, 2, '.') }} maschi e {{ number_format($comune->affluenza->elettori_f, 0, 2, '.') }} femmine)
            </td>
        </tr>

        <tr class="tr-standard">
            <td class="align-top">
                Votanti
            </td>
            <td>
                <b>{{ number_format($comune->affluenza->voti_23, 0, 2, '.') }}</b>
                ({{ number_format($comune->affluenza->voti_23_m, 0, 2, '.') }} maschi e {{ number_format($comune->affluenza->voti_23_f, 0, 2, '.') }} femmine)
            </td>
        </tr>

        <tr class="tr-standard">
            <td class="align-top">
                Affluenza totale
            </td>
            <td>
                <div class="bg-sky-200 relative h-8">
                    <div class="bg-sky-400 absolute h-8 p-2 text-white font-bold text-center" style="width: {{ $comune->affluenza->percentuale_23 }}%;"">{{ $comune->affluenza->percentuale_23 }}%</div> 
                </div>
            </td>
        </tr>
    </table>



    @foreach($comune->collegiUninominaliCamera as $collegio)
        <h2 class="section">Candidati {{ $collegio->nome }}</h2>

        @include('partials.geo.candidati_uninominale', ['candidature' => $collegio->candidature ])
    @endforeach

    <h2 class="section">Candidati {{ $comune->collegiUninominaliCamera->first()->collegioPlurinominale->nome }}</h2>

    @include('partials.geo.candidati_plurinominale', ['candidature' => $comune->collegiUninominaliCamera->first()->collegioPlurinominale->candidature ])


    @foreach($comune->collegiUninominaliSenato as $collegio)
        <h2 class="section">Candidati {{ $collegio->nome }}</h2>

        @include('partials.geo.candidati_uninominale', ['candidature' => $collegio->candidature ])
    @endforeach

    <h2 class="section">Candidati {{ $comune->collegiUninominaliSenato->first()->collegioPlurinominale->nome }}</h2>

    @include('partials.geo.candidati_plurinominale', ['candidature' => $comune->collegiUninominaliCamera->first()->collegioPlurinominale->candidature ])

@endsection