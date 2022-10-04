@extends('layout')

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('liste'), 'label' => 'Liste' ]
        ],
        'title' => $lista->nome
    ])

    <h2 class="section">Candidature</h2>

    <table class="table">
        <tr class="tr-heading">
            <th>Tipologia</th>
            <th>Numero candidature</th>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('lista_collegi_uninominali_camera', $lista) }}">Uninominale Camera</a></td>
            <td>{{ $lista->candidatureCollegiUninominaliCamera()->count() }}</td>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('lista_collegi_plurinominali_camera', $lista) }}">Plurinominale Camera</a></td>
            <td>{{ $lista->candidatureCollegiPlurinominaliCamera()->count() }}</td>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('lista_collegi_uninominali_senato', $lista) }}">Uninominale Senato</a></td>
            <td>{{ $lista->candidatureCollegiUninominaliSenato()->count() }}</td>
        </tr>
        <tr class="tr-standard">
            <td><a class="anchor" href="{{ route('lista_collegi_plurinominali_senato', $lista) }}">Plurinominale Senato</a></td>
            <td>{{ $lista->candidatureCollegiPlurinominaliSenato()->count() }}</td>
        </tr>
    </table>

@endsection