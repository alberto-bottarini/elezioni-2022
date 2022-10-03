@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('circoscrizioni_camera'), 'label' => 'Circoscrizioni Camera' ],
        ],
        'title' => $circoscrizione->nome
    ])

    <h2 class="section">Collegi plurinominali</h2>
    
    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Numero collegi uninominali</th>
        </tr>
        @foreach($collegiPlurinominali as $collegio)
            <tr class="tr-standard">
                <td><a href="{{ route('collegio_plurinominale_camera', $collegio) }}" class="anchor">@svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $collegio->nome }}</a></td>
                <td>{{ $collegio->collegi_uninominali_count }}</td>
            </tr>
        @endforeach
    </table>
    
@endsection