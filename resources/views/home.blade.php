@extends('layout')

@section('content')

    <div x-data="searchComponent">
        <div class="section">Ricerca</div>

        <input type="text" class="h-12 w-full border border-sky-400 text-xl px-2 my-2 shadow" autocomplete="false"
            x-model="search"
            @input.throttle="change" placeholder="Inserisci i parametri di ricerca">

        <div x-html="results"></div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('searchComponent', () => ({
                    results: '',

                    search: null,

                    async change() {
                        if(this.search.length === 0) {
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
                    @svg('heroicon-o-globe-alt', 'w-6 h-6 mb-2 inline')<br>
                    Elenco circoscrizioni Camera
                </a>
            </div>
        </div>

        <div class="w-1/2 md:w-1/4 lg:w-1/6">
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('circoscrizioni_senato') }}" class="block py-4 px-2">
                    @svg('heroicon-o-globe-alt', 'w-6 h-6 mb-2 inline')<br>
                    Elenco circoscrizioni Senato
                </a>
            </div>
        </div>

    </div>
@endsection