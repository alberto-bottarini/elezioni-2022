@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Ripartizioni Estero | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati delle ripartizioni estere'
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            
        ],
        'title' => 'Ripartizioni Estero'
    ])


    <h2 class="section">Ripartizioni</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($ripartizioni as $ripartizione)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('ripartizione_estero', $ripartizione) }}" class="anchor">
                        @svg('heroicon-o-globe-alt', 'w-5 h-5 inline-block') {{ $ripartizione->nome }}</a>
                </td>
            </tr>
        @endforeach 
    </table>
    
@endsection