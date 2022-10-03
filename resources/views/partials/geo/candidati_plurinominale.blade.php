<table class="table">
    <tr class="tr-heading">
        <th>Nome</th>
        <th>Anno di nascita</th>
    </tr>
    @foreach($candidature as $candidatura)
        <tr class="tr-subheading">
            <td colspan="2">{{ $candidatura->lista->nome }}</td>
        </tr>
        @foreach($candidatura->candidati as $candidato)
            <tr class="tr-standard">
                <td><a href="" class="anchor">@svg('heroicon-o-user-circle', 'w-5 h-5 inline-block') {{ $candidato->cognome }} {{ $candidato->nome }} {{ $candidato->altro_1 }} {{ $candidato->altro_2 }}</a></td>
                <td>{{ $candidatura->id }} nato nel {{ $candidato->anno_nascita }} a {{ $candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    @endforeach
</table>