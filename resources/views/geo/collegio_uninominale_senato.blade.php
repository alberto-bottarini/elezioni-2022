@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_senato'), 'label' => 'Circoscrizioni Senato' ],
            [ 'route' => route('circoscrizione_senato', $collegio->collegioPlurinominale->circoscrizione), 'label' => $collegio->collegioPlurinominale->circoscrizione->nome ],
            [ 'route' => route('collegio_plurinominale_senato', $collegio->collegioPlurinominale), 'label' => $collegio->collegioPlurinominale->nome ],
            
        ],
        'title' => $collegio->nome
    ])

    <h2 class="section">Candidati</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($collegio->candidature as $candidatura)
            <tr class="tr-subheading">
                <td colspan="2">{{ $candidatura->coalizione->nome }}</td>
            </tr>
            
            {{-- @foreach($candidatura->liste as $lista)
                <tr class="tr-subheading">
                    <td colspan="2">{{ $lista->nome }}</td>
                </tr>
            @endforeach --}}
            
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td><a href="#" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->cognome }} {{ $candidatura->candidato->nome }} {{ $candidatura->candidato->altro_1 }} {{ $candidatura->candidato->altro_2 }}</a></td>
                <td>nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Comuni</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($collegio->comuni as $comune)
            <tr class="tr-standard">
                <td><a href="{{ route('comune', $comune) }}" class="anchor">@svg('heroicon-o-map-pin', 'w-5 h-5 inline-block'){{ $comune->nome }}</td>
            </tr>
        @endforeach
    </table>


@endsection