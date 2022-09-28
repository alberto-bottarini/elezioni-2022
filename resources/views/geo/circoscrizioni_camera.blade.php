@extends('layout')

@section('content')
    <h1 class="text-2xl mb-2">Elenco Circoscrizioni Camera</h1>

    <table class="w-full text-sm">
        <tr class="bg-sky-400 text-white">
            <th class="p-1 text-left">Nome</th>
            <th class="p-1 text-left">Numero collegi plurinominali</th>
        </tr>
        @foreach($circoscrizioni as $circoscrizione)
            <tr class="even:bg-slate-100 odd:bg-slate-200">
                <td class="p-1"><a href="{{ route('circoscrizione_camera', $circoscrizione) }}" class="anchor">{{ $circoscrizione->nome }}</a></td>
                <td class="p-1">{{ $circoscrizione->collegi_plurinominali_count }}</td>
            </tr>
        @endforeach
    </table>

@endsection