@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('coalizioni'), 'label' => 'Coalizioni' ],
            [ 'route' => route('coalizione', $coalizione), 'label' => $coalizione->nome ],
        ],
        'title' => 'Candidature Coalizione collegi uninominali Senato'
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($coalizione->candidatureCollegiUninominaliSenato as $candidatura)
            <tr class="tr-subheading">
                <td colspan="2">{{ $candidatura->collegio->nome }}</td>
            </tr>
            <tr class="tr-standard">
                <td><a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a></td>
                <td>nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    </table>

@endsection