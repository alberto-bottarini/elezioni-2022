@extends('layout')

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