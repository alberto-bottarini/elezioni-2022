@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Elenco Candidati | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022'
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ]
        ],
        'title' => 'Candidati'
    ])

    <h2 class="section">Candidati</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($candidati as $candidato)
            <tr class="tr-standard">
                <td><a href="{{ route('candidato', $candidato) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidato->nome }}</td>
                <td>nato nel {{ $candidato->anno_nascita }} a {{ $candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    </table>

@endsection