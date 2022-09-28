@extends('layout')

@section('content')

    <div class="my-2 text-sm">
        <a href="{{ route('home') }}" class="anchor">Home</a> ~ 
        <a href="{{ route('circoscrizioni_camera') }}" class="anchor">Circoscrizioni Camera</a> ~ 
        <h1 class="text-2xl mb-2 inline-block">{{ $circoscrizione->nome }}</h1>
    </div>

    <h2>Collegi plurinominali</h2>
    
    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
            <th class="p-1 text-left">Numero collegi uninominali</th>
        </tr>
        @foreach($collegiPlurinominali as $collegio)
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td class="p-1"><a href="{{ route('collegio_plurinominale_camera', $collegio) }}" class="anchor">{{ $collegio->nome }}</a></td>
                <td class="p-1">{{ $collegio->collegi_uninominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection