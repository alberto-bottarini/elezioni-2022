@extends('layout')

@section('content')

    <div class="my-2 text-sm">
        <a href="{{ route('home') }}" class="anchor">Home</a> ~ 
        <a href="{{ route('circoscrizioni_camera') }}" class="anchor">Circoscrizioni Camera</a> ~ 
        <a href="{{ route('circoscrizione_camera', $collegio->collegioPlurinominale->circoscrizione) }}" class="anchor">{{ $collegio->collegioPlurinominale->circoscrizione->nome }}</a> ~ 
        <a href="{{ route('collegio_plurinominale_camera', $collegio->collegioPlurinominale) }}" class="anchor">{{ $collegio->collegioPlurinominale->nome }}</a> ~ 
        <h1 class="text-2xl mb-2 inline-block">{{ $collegio->nome }}</h1>
    </div>

    <h2>Candidati</h2>

    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
            <th class="p-1 text-left">Anno di nascita</th>
        </tr>
        @foreach($collegio->candidature as $candidatura)
            <tr>
                <td class="bg-sky-300 text-white p-1" colspan="2">Coalizione: {{ $candidatura->coalizione->nome }}</td>
            </tr>
            
            @foreach($candidatura->liste as $lista)
                <tr>
                    <td class="bg-sky-400 text-white p-1" colspan="2">Lista: {{ $lista->nome }}</td>
                </tr>
            @endforeach
            
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td class="p-1"><a href="#" class="anchor">{{ $candidatura->candidato->cognome }} {{ $candidatura->candidato->nome }} {{ $candidatura->candidato->altro_1 }} {{ $candidatura->candidato->altro_2 }}</a></td>
                <td class="p-1">nato nel {{ $candidatura->candidato->anno_nascita }} a {{ $candidatura->candidato->luogo_nascita }}</a></td>
            </tr>
        @endforeach
    </table>

    <h2>Comuni</h2>

    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
        </tr>
        @foreach($collegio->comuni as $comune)
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td class="p-1"><a href="#" class="anchor">{{ $comune->nome }}</td>
            </tr>
        @endforeach
    </table>


@endsection