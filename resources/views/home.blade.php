@extends('layout')

@section('meta')

@endsection

@section('content')

    <div x-data="searchComponent">
        <div class="section">Ricerca</div>

        <input type="text" class="h-12 w-full border border-sky-400 text-xl px-2 my-4 shadow" autocomplete="false"
            x-model="search"
            @input.throttle="change" placeholder="Inserisci i parametri di ricerca (almeno 3 caratteri)">

        <div x-html="results"></div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('searchComponent', () => ({
                    results: '',

                    search: null,

                    async change() {
                        if(this.search.length < 3) {
                            this.results = ''
                            return;
                        }
                        await axios.post('/ricerca', { 'search' : this.search }).then(response => {
                            this.results = response.data;
                        })
                    }
                }));
            });
        </script>

    </div>

    <div class="section">Sezioni del portale</div>

    <div class="flex flex-wrap gap-2 mt-4">

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('circoscrizioni_camera') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco circoscrizioni Camera
                </a>
            </div>
        </div>

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('circoscrizioni_senato') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco circoscrizioni Senato
                </a>
            </div>
        </div>

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('comuni') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map-pin', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco comuni
                </a>
            </div>
        </div>

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('liste') }}" class="block py-4 px-2">
                    @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco liste
                </a>
            </div>
        </div>

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('candidati') }}" class="block py-4 px-2">
                    @svg('heroicon-o-user-circle', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco candidati
                </a>
            </div>
        </div>


    </div>

    <div class="section">Aggiornamenti del portale</div>

    <ul class="text-sm mt-4">
        <li><b>04/10/22</b> Create le pagine dei candidati</li>
        <li><b>03/10/22</b> Create le pagine dei comuni</li>
    </ul>
@endsection