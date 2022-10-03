@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ]
        ],
        'title' => 'Comuni'
    ])

    <h2 class="section">Comuni</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($comuniPerProvincia as $provincia => $comuni)
            <tr class="tr-subheading">
                <td>{{ $provincia }}</td>
            </tr>
            @foreach($comuni as $comune)
                <tr class="tr-standard">
                    <td><a href="{{ route('comune', $comune) }}" class="anchor">@svg('heroicon-o-map-pin', 'w-5 h-5 inline-block'){{ $comune->nome }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

@endsection