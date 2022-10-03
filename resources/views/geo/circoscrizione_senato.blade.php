@extends('layout')

@section('content')

@include('partials.breadcrumb', [
    'crumbs' => [
        [ 'route' => route('home'), 'label' => 'Home' ],
        [ 'route' => route('circoscrizioni_senato'), 'label' => 'Circoscrizioni Senato' ],
    ],
    'title' => $circoscrizione->nome
])

    <h2 class="section">Collegi plurinominali</h2>
    
    <table class="w-full text-sm">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Numero collegi uninominali</th>
        </tr>
        @foreach($collegiPlurinominali as $collegio)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_plurinominale_senato', $collegio) }}" class="anchor">{{ $collegio->nome }}</a></td>
                <td>{{ $collegio->collegi_uninominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection