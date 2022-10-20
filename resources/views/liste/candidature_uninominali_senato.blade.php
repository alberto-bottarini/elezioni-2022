@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('liste'), 'label' => 'Liste' ],
            [ 'route' => route('lista', $lista), 'label' => $lista->nome ],
        ],
        'title' => 'Candidature collegi plurinominali Senato'
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
            <th>Coalizione</th>
        </tr>
        @foreach($lista->candidatureCollegiUninominaliSenato as $candidatura)
            <tr class="tr-subheading">
                <td colspan="3">{{ $candidatura->collegio->nome }}</td>
            </tr>
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td><a href="#" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a></td>
                <td>nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
                <td class="text-xs">{{ $candidatura->coalizione->nome }}</td>
            </tr>
        @endforeach
    </table>

@endsection