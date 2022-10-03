@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('comuni'), 'label' => 'Comuni' ],
        ],
        'title' => $comune->nome . ' (' . $comune->provincia.')'
    ])

    <h2 class="section">Collegi Camera</h2>

    <table class="table">
        <tr class="tr-standard">
            <td>
                Collegio uninominale
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_uninominale_camera', ['collegioUninominale' => $comune->collegioUninominaleCamera ]) }}">
                    @svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $comune->collegioUninominaleCamera->nome }}
                </a>
            </td>
        </tr>
        <tr class="tr-standard">
            <td>
                Collegio plurinominale
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_plurinominale_camera', ['collegioPlurinominale' => $comune->collegioUninominaleCamera->collegioPlurinominale ]) }}">
                    @svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $comune->collegioUninominaleCamera->collegioPlurinominale->nome }}
                </a>
            </td>
        </tr>
    </table>

    <h2 class="section">Collegi Senato</h2>

    <table class="table">
        <tr class="tr-standard">
            <td>
                Collegio uninominale
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_uninominale_senato', ['collegioUninominale' => $comune->collegioUninominaleSenato ]) }}">
                    @svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $comune->collegioUninominaleSenato->nome }}
                </a>
            </td>
        </tr>
        <tr class="tr-standard">
            <td>
                Collegio plurinominale
            </td>
            <td>
                <a class="anchor" href="{{ route('collegio_plurinominale_senato', ['collegioPlurinominale' => $comune->collegioUninominaleSenato->collegioPlurinominale ]) }}">
                    @svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $comune->collegioUninominaleSenato->collegioPlurinominale->nome }}
                </a>
            </td>
        </tr>
    </table>

@endsection