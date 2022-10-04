@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ]
        ],
        'title' => 'Liste'
    ])

    <h2 class="section">Liste</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
        </tr>
        @foreach($liste as $lista)
            <tr class="tr-standard">
                <td><a href="{{ route('lista', $lista) }}" class="anchor">@svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block') {{ $lista->nome }}</td>
            </tr>
        @endforeach
    </table>

@endsection