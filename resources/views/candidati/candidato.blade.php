@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $candidato->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati di ' . $candidato->nome,
        'og' => asset('og/candidato/' . $candidato->id . '.png'),
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            ['route' => route('home'), 'label' => 'Home'],
            ['route' => route('candidati'), 'label' => 'Candidati'],
        ],
        'title' => $candidato->nome,
    ])

    @if ($candidato->candidatureCollegiUninominaliCamera->count())
        <h2 class="section">Candidature uninominale Camera</h2>

        <table class="table">
            <tr class="tr-heading">
                <th>Collegio uninominale</th>
                <th>Coalizione</th>
            </tr>
            @foreach ($candidato->candidatureCollegiUninominaliCamera as $candidatura)
                <tr class="tr-standard">
                    <td><a href="{{ route('collegio_uninominale_camera', $candidatura->collegio) }}"
                            class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $candidatura->collegio->nome }}</a></td>
                    <td class="text-xs">{{ $candidatura->coalizione->nome }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if ($candidato->candidatureCollegiPlurinominaliCamera->count())
        <h2 class="section">Candidature plurinominale Camera</h2>

        <table class="table">
            <tr class="tr-heading">
                <th>Collegio plurinominale</th>
                <th>Lista</th>
            </tr>
            @foreach ($candidato->candidatureCollegiPlurinominaliCamera as $candidatura)
                <tr class="tr-standard">
                    <td><a href="{{ route('collegio_plurinominale_camera', $candidatura->collegioPlurinominale) }}"
                            class="anchor">@svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $candidatura->collegioPlurinominale->nome }}</td>
                    <td>{{ $candidatura->lista->nome }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if ($candidato->candidatureCollegiUninominaliSenato->count())
        <h2 class="section">Candidature uninominale Senato</h2>

        <table class="table">
            <tr class="tr-heading">
                <th>Collegio uninominale</th>
                <th>Coalizione</th>
            </tr>
            @foreach ($candidato->candidatureCollegiUninominaliSenato as $candidatura)
                <tr class="tr-standard">
                    <td><a href="{{ route('collegio_uninominale_senato', $candidatura->collegio) }}"
                            class="anchor">@svg('heroicon-o-cursor-arrow-rays', 'w-5 h-5 inline-block') {{ $candidatura->collegio->nome }}</a></td>
                    <td class="text-xs">{{ $candidatura->coalizione->nome }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if ($candidato->candidatureCollegiPlurinominaliSenato->count())
        <h2 class="section">Candidature plurinominale Senato</h2>

        <table class="table">
            <tr class="tr-heading">
                <th>Collegio plurinominale</th>
                <th>Lista</th>
            </tr>
            @foreach ($candidato->candidatureCollegiPlurinominaliSenato as $candidatura)
                <tr class="tr-standard">
                    <td><a href="{{ route('collegio_plurinominale_senato', $candidatura->collegioPlurinominale) }}"
                            class="anchor">@svg('heroicon-o-cursor-arrow-ripple', 'w-5 h-5 inline-block') {{ $candidatura->collegioPlurinominale->nome }}</td>
                    <td>{{ $candidatura->lista->nome }}</td>
                </tr>
            @endforeach
        </table>
    @endif

@endsection
