<table class="table">
    <tr class="tr-heading">
        <th>Nome</th>
        <th>Anno di nascita</th>
    </tr>
    @foreach($candidature as $candidatura)
        <tr class="tr-subheading">
            <td colspan="2">{{ $candidatura->coalizione->nome }}</td>
        </tr>

        <tr class="even:bg-slate-100 odd:bg-slate-200">
            <td><a href="{{ route('candidato', $candidatura->candidato) }}" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidatura->candidato->cognome }} {{ $candidatura->candidato->nome }} {{ $candidatura->candidato->altro_1 }} {{ $candidatura->candidato->altro_2 }}</a></td>
            <td>nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
        </tr>
    @endforeach
</table>