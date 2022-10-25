@extends('layout')

@section('meta')
    @include('partials.meta', [
        'title' => 'Elezioniamo 2022 | Tutti i dati delle Elezioni Politiche 2022'
    ])
@endsection

@section('content')

    <div class="section">Elezioniamo 2022</div>
    <p>Benvenuto in Elezioniamo 2022, il portale che mette a disposizione di tutti i dati delle Elezioni Politiche 2022.</p>
    <p>Utilizza il form di ricerca qua sotto per cercare un comune, una lista o un candidato, oppure naviga con i pulsanti piú in basso.</p>

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

    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 mt-4 gap-2">

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('risultati_camera') }}" class="block py-4 px-2">
                    @svg('heroicon-o-building-library', 'w-5 h-5 inline-block mb-2')<br>
                    Risultati Definitivi Camera
                </a>
            </div>
        </div>
        
        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('circoscrizioni_camera') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco circoscrizioni Camera
                </a>
            </div>
        </div>

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('risultati_senato') }}" class="block py-4 px-2">
                    @svg('heroicon-o-building-library', 'w-5 h-5 inline-block mb-2')<br>
                    Risultati Definitivi Senato
                </a>
            </div>
        </div>

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('circoscrizioni_senato') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco circoscrizioni Senato
                </a>
            </div>
        </div>

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('comuni') }}" class="block py-4 px-2">
                    @svg('heroicon-o-map-pin', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco comuni
                </a>
            </div>
        </div>

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('liste') }}" class="block py-4 px-2">
                    @svg('heroicon-o-list-bullet', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco liste
                </a>
            </div>
        </div>

        <div>
            <div class="border border-sky-400 bg-sky-200 text-center text-sm hover:bg-sky-400">
                <a href="{{ route('coalizioni') }}" class="block py-4 px-2">
                    @svg('heroicon-o-queue-list', 'w-5 h-5 inline-block mb-2')<br>
                    Elenco coalizioni
                </a>
            </div>
        </div>

        <div>
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
        <li><b>25/10/22</b> Importati e sanificati i risultati del Senato</li>
        <li><b>24/10/22</b> Importati risultati Camera per Val d'Aosta</li>
        <li><b>20/10/22</b> Importati e sanificati i risultati della Camera</li>
        <li><b>18/10/22</b> Aggiunte i risultati nelle pagine dei comuni + corretti un po' di bug</li>
        <li><b>13/10/22</b> Aggiunte sitemap</li>
        <li><b>07/10/22</b> Aggiunte le affluenze nelle pagine dei comuni + aggiornati metatag delle pagine</li>
        <li><b>06/10/22</b> Aggiornati metatag delle pagine</li>
        <li><b>05/10/22</b> Sistemata relazione tra Comuni e Collegio Uninominali per le grandi città metropolitane</li>
        <li><b>04/10/22</b> Create le pagine dei candidati</li>
        <li><b>03/10/22</b> Create le pagine dei comuni</li>
    </ul>
@endsection