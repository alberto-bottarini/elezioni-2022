<table class="table">
    <tr class="tr-heading">
        <th>Nome</th>
        <th>Coalizione</th>
        <th>Anno/Luogo di nascita</th>
    </tr>
    @foreach($candidature as $candidatura)
        <tr class="tr-standard">
            <td><a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->nome }}</a></td>
            <td>
                <a class="anchor" href="{{ route('coalizione', $candidatura->coalizione) }}">
                    @svg('heroicon-o-queue-list', 'w-5 h-5 inline-block'){{ $candidatura->coalizione->nome }}
                </a>
            </td>
            <td>{{ $candidatura->candidato->data_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
        </tr>
    @endforeach
</table>