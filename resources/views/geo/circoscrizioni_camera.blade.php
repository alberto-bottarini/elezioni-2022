@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Circoscrizioni Camera | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022'
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
        ],
        'title' => 'Circoscrizioni Camera'
    ])

    <h2 class="section">Circoscrizioni</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($circoscrizioni as $circoscrizione)
            <tr class="tr-standard">
                <td><a href="{{ route('circoscrizione_camera', $circoscrizione) }}" class="anchor">@svg('heroicon-o-map', 'w-5 h-5 inline-block') {{ $circoscrizione->nome }}</a></td>
            </tr>
        @endforeach
    </table>

@endsection