@extends('layout')

@section('content')

    <div class="my-2 text-sm">
        <a href="{{ route('home') }}" class="anchor">Home</a> ~ 
        <a href="{{ route('circoscrizioni_senato') }}" class="anchor">Circoscrizioni Senato</a> ~ 
        <a href="{{ route('circoscrizione_senato', $collegio->circoscrizione) }}" class="anchor">{{ $collegio->circoscrizione->nome }}</a> ~ 
        <h1 class="text-2xl mb-2 inline-block">{{ $collegio->nome }}</h1>
    </div>

    <h2>Collegi uninominali</h2>
    
    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
        </tr>
        @foreach($collegiUninominali as $collegio)
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td class="p-1"><a href="{{ route('collegio_uninominale_senato', $collegio) }}" class="anchor">{{ $collegio->nome }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2>Candidati</h2>

    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
            <th class="p-1 text-left">Anno di nascita</th>
        </tr>
        @foreach($candidature as $candidatura)
            <tr>
                <td class="bg-sky-300 text-white p-1" colspan="2">{{ $candidatura->lista->nome }}</td>
            </tr>
            @foreach($candidatura->candidati as $candidato)
                <tr class="even:bg-slate-100 odd:bg-slate-200">
                    <td class="p-1"><a href="{{ route('collegio_uninominale_senato', $collegio) }}" class="anchor">{{ $candidato->cognome }} {{ $candidato->nome }} {{ $candidato->altro_1 }} {{ $candidato->altro_2 }}</a></td>
                    <td class="p-1">nato nel {{ $candidato->anno_nascita }} a {{ $candidato->luogo_nascita }}</a></td>
                </tr>
            @endforeach
        @endforeach
    </table>

@endsection