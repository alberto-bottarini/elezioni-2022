@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_senato'), 'label' => 'Circoscrizioni Senato' ],
            [ 'route' => route('circoscrizione_senato', $collegio->circoscrizione), 'label' => $collegio->circoscrizione->nome ],
        ],
        'title' => $collegio->nome
    ])

    <h2 class="section">Collegi uninominali</h2>
    
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($collegiUninominali as $collegio)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_uninominale_senato', $collegio) }}" class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $collegio->nome }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Candidati</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($candidature as $candidatura)
            <tr class="tr-subheading">
                <td colspan="2">{{ $candidatura->lista->nome }}</td>
            </tr>
            @foreach($candidatura->candidati as $candidato)
                <tr class="tr-standard">
                    <td><a href="{{ route('collegio_uninominale_senato', $collegio) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidato->cognome }} {{ $candidato->nome }} {{ $candidato->altro_1 }} {{ $candidato->altro_2 }}</a></td>
                    <td>nato nel {{ $candidato->anno_nascita }} a {{ $candidato->luogo_nascita }}</a></td>
                </tr>
            @endforeach
        @endforeach
    </table>

@endsection