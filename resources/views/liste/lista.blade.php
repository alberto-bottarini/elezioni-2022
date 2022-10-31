@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $lista->nome . ' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati della lista ' . $lista->nome,
        'og' => asset('og/lista-' . $lista->id . '.png'),
    ])
@endsection

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

    @if($risultatoCamera || $risultatoSenato)
        <h2 class="section">Risultati nazionali</h2>
        <table class="table">
            <tr class="tr-heading">
                <th>Camera</th>
                <th>Voti</th>
                <th>Percentuale</th>
                <th>Grafico</th>
            </tr>
            @if($risultatoCamera)
                <tr class="tr-standard">
                    <td>Camera</td>
                    <td>{{ format_voti($risultatoCamera->voti) }}</td>
                    <td>{{ format_percentuali($risultatoCamera->percentuale) }}</td>
                    <td>
                        <div class="h-[60px] w-[60px]">
                            @php $rand = uniqid(); @endphp
                            <canvas id="chart-{{ $rand }}"></canvas>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var data = {
                                        labels: [
                                            "{{ $lista->nome }}",
                                            "Altri"
                                        ],
                                        datasets: [{
                                            label: 'Risultati Camera',
                                            data: [
                                                {{ $risultatoCamera->percentuale }},
                                                {{ 100 - $risultatoCamera->percentuale }}
                                            ],
                                            backgroundColor: [
                                                'rgb(125 211 252)',
                                                'rgb(240 249 255)',
                                            ],
                                            borderColor: 'rgb(71 85 105)', //bg-slate-600
                                            borderWidth: 1,
                                            hoverOffset: 4
                                        }]
                                    };
                                    var ctx = document.getElementById('chart-{{ $rand }}');
                                    var myChart = new ChartJS(ctx, {
                                        type: 'pie',
                                        data: data,
                                        options: {
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: false
                                                }
                                            }
                                        }
                                    });
                                }, false);
                            </script>
                        </div>
                    </td>
                </tr>
            @endif
            @if($risultatoSenato)
                <tr class="tr-standard">
                    <td>Senato</td>
                    <td>{{ format_voti($risultatoSenato->voti) }}</td>
                    <td>{{ format_percentuali($risultatoSenato->percentuale) }}</td>
                    <td>
                        <div class="h-[60px] w-[60px]">
                            @php $rand = uniqid(); @endphp
                            <canvas id="chart-{{ $rand }}"></canvas>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var data = {
                                        labels: [
                                            "{{ $lista->nome }}",
                                            "Altri"
                                        ],
                                        datasets: [{
                                            label: 'Risultati Camera',
                                            data: [
                                                {{ $risultatoSenato->percentuale }},
                                                {{ 100 - $risultatoSenato->percentuale }}
                                            ],
                                            backgroundColor: [
                                                'rgb(125 211 252)',
                                                'rgb(240 249 255)',
                                            ],
                                            borderColor: 'rgb(71 85 105)', //bg-slate-600
                                            borderWidth: 1,
                                            hoverOffset: 4
                                        }]
                                    };
                                    var ctx = document.getElementById('chart-{{ $rand }}');
                                    var myChart = new ChartJS(ctx, {
                                        type: 'pie',
                                        data: data,
                                        options: {
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: false
                                                }
                                            }
                                        }
                                    });
                                }, false);
                            </script>
                        </div>
                    </td>
                </tr>
            @endif
        </table>
    @endif

    @if($risultatiCircoscrizioniCamera->count())
        <h2 class="section">Risultati Camera per Circoscrizione</h2>
        <table class="table">
            <tr class="tr-heading">
                <th>Circoscrizione</th>
                <th>Voti</th>
                <th>Percentuale</th>
                <th>Grafico</th>
            </tr>
            @foreach($risultatiCircoscrizioniCamera as $risultato)
                <tr class="tr-standard">
                    <td><a href="{{ route('circoscrizione_camera', $risultato->circoscrizione) }}" class="anchor">@svg('heroicon-o-map', 'w-5 h-5 inline-block') {{ $risultato->circoscrizione->nome }}</a></td>
                    <td>{{ format_voti($risultato->voti) }}</td>
                    <td>{{ format_percentuali($risultato->percentuale) }}</td>
                    <td>
                        <div class="h-[60px] w-[60px]">
                            @php $rand = uniqid(); @endphp
                            <canvas id="chart-{{ $rand }}"></canvas>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var data = {
                                        labels: [
                                            "{{ $lista->nome }}",
                                            "Altri"
                                        ],
                                        datasets: [{
                                            label: 'Risultati Camera',
                                            data: [
                                                {{ $risultato->percentuale }},
                                                {{ 100 - $risultato->percentuale }}
                                            ],
                                            backgroundColor: [
                                                'rgb(125 211 252)',
                                                'rgb(240 249 255)',
                                            ],
                                            borderColor: 'rgb(71 85 105)', //bg-slate-600
                                            borderWidth: 1,
                                            hoverOffset: 4
                                        }]
                                    };
                                    var ctx = document.getElementById('chart-{{ $rand }}');
                                    var myChart = new ChartJS(ctx, {
                                        type: 'pie',
                                        data: data,
                                        options: {
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: false
                                                }
                                            }
                                        }
                                    });
                                }, false);
                            </script>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($risultatiCircoscrizioniSenato->count())
        <h2 class="section">Risultati Senato per Circoscrizione</h2>
        <table class="table">
            <tr class="tr-heading">
                <th>Circoscrizione</th>
                <th>Voti</th>
                <th>Percentuale</th>
                <th>Grafico</th>
            </tr>
            @foreach($risultatiCircoscrizioniSenato as $risultato)
                <tr class="tr-standard">
                    <td><a href="{{ route('circoscrizione_senato', $risultato->circoscrizione) }}" class="anchor">@svg('heroicon-o-map', 'w-5 h-5 inline-block') {{ $risultato->circoscrizione->nome }}</a></td>
                    <td>{{ format_voti($risultato->voti) }}</td>
                    <td>{{ format_percentuali($risultato->percentuale) }}</td>
                    <td>
                        <div class="h-[60px] w-[60px]">
                            @php $rand = uniqid(); @endphp
                            <canvas id="chart-{{ $rand }}"></canvas>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var data = {
                                        labels: [
                                            "{{ $lista->nome }}",
                                            "Altri"
                                        ],
                                        datasets: [{
                                            label: 'Risultati Senato',
                                            data: [
                                                {{ $risultato->percentuale }},
                                                {{ 100 - $risultato->percentuale }}
                                            ],
                                            backgroundColor: [
                                                'rgb(125 211 252)',
                                                'rgb(240 249 255)',
                                            ],
                                            borderColor: 'rgb(71 85 105)', //bg-slate-600
                                            borderWidth: 1,
                                            hoverOffset: 4
                                        }]
                                    };
                                    var ctx = document.getElementById('chart-{{ $rand }}');
                                    var myChart = new ChartJS(ctx, {
                                        type: 'pie',
                                        data: data,
                                        options: {
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: false
                                                }
                                            }
                                        }
                                    });
                                }, false);
                            </script>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

@endsection