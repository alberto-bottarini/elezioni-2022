<table class="table">
    <tr class="tr-heading">
        <th>Nome Coalizione</th>
        <th>Voti</th>
        <th>%</th>
        <th>Liste</th>
    </tr>

    @foreach ($risultatiPerCoalizione as $coalizioneId => $coalizioneItem)
        <tr class="tr-standard">
            <td>
                <a href="{{ route('coalizione', $coalizioni->firstWhere('id', $coalizioneId)) }}"class="anchor">
                    @svg('heroicon-o-queue-list', 'w-5 h-5 inline-block')
                    {{ $coalizioni->firstWhere('id', $coalizioneId)->nome }}
                </td>
            <td>{{ format_voti($coalizioneItem['voti']) }}</td>
            <td>{{ format_percentuali($coalizioneItem['percentuale']) }}</td>
            <td>
                <table class="table table-small">
                    <tr class="tr-heading">
                        <th class="w-5/6">Nome Lista</th>
                        <th class="w-1/6">Voti</th>
                        <th class="w-1/6">%</th>
                    </tr>
                    @foreach ($coalizioneItem['risultati'] as $risultato)
                        <tr class="tr-standard">
                            <td class="w-4/6">
                                <a class="anchor" href="{{ route('lista', $risultato->lista) }}">
                                    @svg('heroicon-o-list-bullet', 'w-4 h-4 inline-block mr-2'){{ $risultato->lista->nome }}
                                </a>
                            </td>
                            <td class="w-1/6">
                                {{ format_voti($risultato->voti) }}
                            </td>
                            <td class="w-1/6">
                                {{ format_percentuali($risultato->percentuale) }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @endforeach

</table>