@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => $nazione->nome .' | Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022',
        'description' =>
            'Scopri grazie ad Elezioniamo le candidature e i risultati in ' . $nazione->nome
    ])
@endsection

@section('content')

    @include('partials.breadcrumb', [
        'crumbs' => [
            [ 'route' => route('home'), 'label' => 'Home' ],
            [ 'route' => route('ripartizioni_estero'), 'label' => 'Ripartizioni estero' ],
            [ 'route' => route('ripartizione_estero', $nazione->ripartizione), 'label' => $nazione->ripartizione->nome ]
            
        ],
        'title' => $nazione->nome
    ])

    <h2 class="section">Voti Camera</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Lista</th>
            <th>Voti</th>
            <th>%</th>
            <th>Grafico</th>
            <th>Preferenze</th>
        </tr>
        @foreach($nazione->votiCamera as $voto)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('lista', $voto->lista) }}" class="anchor">
                        @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block') {{ $voto->lista->nome }}</a>
                </td>
                <td>
                    {{ format_voti($voto->voti) }}
                </td>
                <td>
                    {{ format_percentuali($voto->percentuale) }}
                </td>
                <td>
                    <div class="h-[60px] w-[60px]">
                        @php $rand = uniqid(); @endphp
                        <canvas id="chart-{{ $rand }}"></canvas>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var data = {
                                    labels: [
                                        @foreach($nazione->votiCamera as $subVoto)
                                            "{{ $subVoto->lista->nome }}",
                                        @endforeach
                                    ],
                                    datasets: [{
                                        label: 'Risultati Camera',
                                        data: [
                                            @foreach($nazione->votiCamera as $subVoto)
                                                "{{ $subVoto->voti }}",
                                            @endforeach
                                        ],
                                        backgroundColor: [
                                            @foreach($nazione->votiCamera as $subVoto)
                                                @if($voto->lista->id == $subVoto->lista->id )
                                                    'rgb(125 211 252)', //bg-sky-600 
                                                @else
                                                    'rgb(240 249 255)', //bg-slate-300
                                                @endif
                                            @endforeach
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
                <td>
                    <table class="table table-small">
                        <tr class="tr-heading">
                            <th>Candidato</th>
                            <th>Preferenze</th>
                        </tr>
                        @foreach($preferenzeCameraPerLista->get($voto->lista->id) as $preferenza)
                            <tr class="tr-standard">
                                <td>{{ $preferenza->candidatura->candidato->nome }}</td>
                                <td>{{ format_voti($preferenza->preferenze) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </table>

    <h2 class="section">Voti Senato</h2>
    <table class="table">
        <tr class="tr-heading">
            <th>Lista</th>
            <th>Voti</th>
            <th>%</th>
            <th>Grafico</th>
            <th>Preferenze</th>
        </tr>
        @foreach($nazione->votiSenato as $voto)
            <tr class="tr-standard">
                <td>
                    <a href="{{ route('lista', $voto->lista) }}" class="anchor">
                        @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block') {{ $voto->lista->nome }}</a>
                </td>
                <td>
                    {{ format_voti($voto->voti) }}
                </td>
                <td>
                    {{ format_percentuali($voto->percentuale) }}
                </td>
                <td>
                    <div class="h-[60px] w-[60px]">
                        @php $rand = uniqid(); @endphp
                        <canvas id="chart-{{ $rand }}"></canvas>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var data = {
                                    labels: [
                                        @foreach($nazione->votiCamera as $subVoto)
                                            "{{ $subVoto->lista->nome }}",
                                        @endforeach
                                    ],
                                    datasets: [{
                                        label: 'Risultati Senato',
                                        data: [
                                            @foreach($nazione->votiCamera as $subVoto)
                                                "{{ $subVoto->voti }}",
                                            @endforeach
                                        ],
                                        backgroundColor: [
                                            @foreach($nazione->votiCamera as $subVoto)
                                                @if($voto->lista->id == $subVoto->lista->id )
                                                    'rgb(125 211 252)', //bg-sky-600 
                                                @else
                                                    'rgb(240 249 255)', //bg-slate-300
                                                @endif
                                            @endforeach
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
                <td>
                    <table class="table table-small">
                        <tr class="tr-heading">
                            <th>Candidato</th>
                            <th>Preferenze</th>
                        </tr>
                        @foreach($preferenzeCameraPerLista->get($voto->lista->id) as $preferenza)
                            <tr class="tr-standard">
                                <td>{{ $preferenza->candidatura->candidato->nome }}</td>
                                <td>{{ format_voti($preferenza->preferenze) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </table>
        
@endsection