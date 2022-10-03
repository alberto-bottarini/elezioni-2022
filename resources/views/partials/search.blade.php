<div class="section">Risultati ricerca</div>

<div>

    @if(!empty($results))
        <table class="table">
            <tr class="tr-heading">
                <th>Nome</th>
                <th>Tipologia</th>
            </tr>
            @foreach($results as $result)
                <tr class="tr-standard">
                    <td>
                        <a href="{{ $result['route'] }}" class="anchor">
                            @if($result['type'] == 'comune')
                                @svg('heroicon-o-map-pin', 'w-5 h-5 inline-block')    
                            @elseif($result['type'] == 'candidato')
                                @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block')   
                            @endif
                            {{ $result['label'] }}
                        </a>
                    </td>
                    <td>{{ $result['type'] }}</td>        
                </tr>
            @endforeach
        </table>
    @else
        <i>Nessun risultato trovato</i>
    @endif

</div>