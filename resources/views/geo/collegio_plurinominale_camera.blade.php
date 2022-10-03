@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_camera'), 'label' => 'Circoscrizioni Camera' ],
            [ 'route' => route('circoscrizione_camera', $collegio->circoscrizione), 'label' => $collegio->circoscrizione->nome ],
        ],
        'title' => $collegio->nome
    ])

    <h2 class="section">Collegi uninominali</h2>
    
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($collegiUninominali as $collegioUninominale)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_uninominale_camera', $collegioUninominale) }}" class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegioUninominale->nome }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Candidati</h2>

    @include('partials.geo.candidati_plurinominale', ['candidature' => $collegio->candidature ])

@endsection