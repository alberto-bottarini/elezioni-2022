<div class="section">Risultati ricerca</div>

<div>

    @if(!empty($results))
        <table class="w-full text-sm">
            <tr class="tr-heading">
                <th>Nome</th>
                <th>Tipologia</th>
            </tr>
            @foreach($results as $result)
                <tr class="tr-standard">
                    <td><a href="{{ $result['route'] }}" class="anchor">{{ $result['label'] }}</a></td>
                    <td>{{ $result['type'] }}</td>        
                </tr>
            @endforeach
        </table>
    @else
        <i>Nessun risultato trovato</i>
    @endif

</div>