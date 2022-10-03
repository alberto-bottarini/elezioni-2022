@extends('layout')

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
            <th>Numero collegi plurinominali</th>
        </tr>
        @foreach($circoscrizioni as $circoscrizione)
            <tr class="tr-standard">
                <td><a href="{{ route('circoscrizione_camera', $circoscrizione) }}" class="anchor">@svg('heroicon-o-map', 'w-5 h-5 inline-block') {{ $circoscrizione->nome }}</a></td>
                <td>{{ $circoscrizione->collegi_plurinominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection