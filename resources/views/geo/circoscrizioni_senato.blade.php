@extends('layout')

@section('content')
    
    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
        ],
        'title' => 'Circoscrizioni Senato'
    ])

    <h2 class="section">Circoscrizioni</h2>

    <table class="table">
        <tr class="bg-sky-400 text-white">
            <th>Nome</th>
            <th>Numero collegi plurinominali</th>
        </tr>
        @foreach($circoscrizioni as $circoscrizione)
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td><a href="{{ route('circoscrizione_senato', $circoscrizione) }}" class="anchor">@svg('heroicon-o-map', 'w-5 h-5 inline-block') {{ $circoscrizione->nome }}</a></td>
                <td>{{ $circoscrizione->collegi_plurinominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection