@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Elenco Coalizioni | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022'
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ]
        ],
        'title' => 'Coalizioni'
    ])

    <h2 class="section">Coalizioni</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($coalizioni as $coalizione)
            <tr class="tr-standard">
                <td><a href="{{ route('coalizione', $coalizione) }}" class="anchor">@svg('heroicon-o-queue-list', 'w-5 h-5 inline-block') {{ $coalizione->nome }}</td>
            </tr>
        @endforeach
    </table>

@endsection