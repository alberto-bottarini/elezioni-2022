@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('coalizioni'), 'label' => 'Coalizioni' ],
            [ 'route' => route('coalizione', $coalizione), 'label' => $coalizione->nome ],
        ],
        'title' => 'Candidature Coalizione collegi uninominali Camera'
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($coalizione->candidatureCollegiUninominaliCamera as $candidatura)
            <tr class="tr-subheading">
                <td colspan="2">{{ $candidatura->collegio->nome }}</td>
            </tr>
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td><a href="#" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a></td>
                <td>nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    </table>

@endsection