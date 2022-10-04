@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('liste'), 'label' => 'Liste' ],
            [ 'route' => route('lista', $lista), 'label' => $lista->nome ],
        ],
        'title' => 'Candidature collegi plurinominali Camera'
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Nome</th>
            <th>Anno di nascita</th>
        </tr>
        @foreach($lista->candidatureCollegiPlurinominaliCamera as $candidatura)
            <tr class="tr-subheading">
                <td colspan="2">{{ $candidatura->collegioPlurinominale->nome }}</td>
            </tr>
            @foreach($candidatura->candidati as $candidato)
                <tr class="tr-standard">
                    <td><a href="" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidato->cognome }} {{ $candidato->nome }} {{ $candidato->altro_1 }} {{ $candidato->altro_2 }}</a></td>
                    <td>nato nel {{ $candidato->anno_nascita }} a {{ $candidato->luogo_nascita }}</a></td>
                </tr>
            @endforeach
        @endforeach
    </table>

@endsection