<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CandidaturaCollegioUninominaleCamera;
use App\Models\CandidaturaCollegioUninominaleCameraLista;
use App\Models\Comune;
use App\Models\Lista;
use App\Models\RisultatoCandidaturaCollegioUninominaleCamera;
use App\Models\RisultatoCandidaturaCollegioUninominaleCameraLista;
use Goutte\Client;
use Illuminate\Console\Command;

class ImportRisultatiValleAosta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-risultati-vda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Risultati Valle Aosta';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://elezioni.regione.vda.it/Elezioni/VotiLista_i.aspx?idele=168');

        $ancoreComuni = $crawler->filter('.elenco-comuni-sezioni-container a');

        $ancoreComuni->each(function ($ancora) use ($client) {
            $comuneUrl = 'https://elezioni.regione.vda.it/Elezioni/' . $ancora->attr('href');
            $comuneCrawler = $client->request('GET', $comuneUrl);
            $nomeComune = explode(' - ', $comuneCrawler->filter('h1.testi')->text())[1];

            if ($nomeComune == 'ANTEY-SAINT-ANDRÉ') {
                $nomeComune = 'ANTEY-SAINT-ANDRE\'';
            } elseif ($nomeComune == 'GRESSONEY-LA-TRINITÉ') {
                $nomeComune = 'GRESSONEY-LA-TRINITE\'';
            } elseif ($nomeComune == 'PRÉ-SAINT-DIDIER') {
                $nomeComune = 'PRE\'-SAINT-DIDIER';
            }
            // $this->warn($nomeComune);

            $comune = Comune::where('nome', $nomeComune)->first();
            if (!$comune) {
                $this->error('Comune ' . $nomeComune . ' non trovato');

                return;
            }

            $righe = $comuneCrawler->filter('.voti-lista');
            $righe->each(function ($riga) use ($comune) {
                $nomeLista = trim($riga->filter('td')->eq(1)->filter('div')->text());

                if ($nomeLista == 'VALLÉE D\'AOSTE - AUTONOMIE PROGRÈS FÉDÉRALISME') {
                    $nomeLista = 'VALLÉE D’AOSTE – AUTONOMIE PROGRÈS FÉDÉRALISME';
                } elseif ($nomeLista == 'FORZA ITALIA - LEGA - FRATELLI D\'ITALIA - NOI MODERATI') {
                    $nomeLista = 'LEGA PER SALVINI PREMIER - FORZA ITALIA - NOI MODERATI - FRATELLI D\'ITALIA';
                }

                $nomeCandidato = $riga->filter('td')->eq(1)->html();
                preg_match('/<\/div>(.*)<div style/s', $nomeCandidato, $matches);
                $nomeCandidato = trim($matches[1]);

                $candidatiMap = [
                    'RONC Loredana Augusta' => 3623,
                    'GUICHARDAZ Erika' => 3624,
                    'RINI Emily Marinella' => 3625,
                    'GIRARDINI Giovanni' => 3626,
                    'IANNI Davide' => 3627,
                    'DE ROSA Loredana' => 3628,
                    'MANES Franco' => 3629,

                    'LERAY Giovanni Guglielmo' => 4351,
                    'VESAN Patrik' => 4352,
                    'PULZ Daria' => 4353,
                    'BICHINI Alessandro' => 4354,
                    'LUCAT Francesco, Maria, Giuseppe, Carlo' => 4355,
                    'SPELGATTI Nicoletta' => 4356,
                    'BARGAN Larisa' => 4357,
                    'ROLLANDIN Augusto' => 4358,
                ];

                // $this->info($lista);
                $voti = trim($riga->filter('td')->eq(2)->text());
                $voti = str_replace('.', '', $voti);
                // $this->info($voti);

                $lista = Lista::where('nome', $nomeLista)->first();
                if (!$lista) {
                    $this->error('Lista ' . $nomeLista . ' non trovato');

                    return;
                }

                $candidato = Candidato::where('id', $candidatiMap[$nomeCandidato])->first();
                if (!$candidato) {
                    $this->error('Candidato ' . $nomeCandidato . ' non trovato');

                    return;
                }

                $candidatura = CandidaturaCollegioUninominaleCamera::where('collegio_uninominale_camera_id', 11)
                    ->where('candidato_id', $candidato->id)
                    ->first();

                RisultatoCandidaturaCollegioUninominaleCamera::unguard();
                RisultatoCandidaturaCollegioUninominaleCamera::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_id' => $candidatura->id,
                ], [
                    'voti_candidato' => 0,
                    'voti' => $voti,
                ]);

                $candidaturaLista = CandidaturaCollegioUninominaleCameraLista::where('candidatura_collegio_uninominale_camera_id', $candidatura->id)
                    ->where('lista_id', $lista->id)
                    ->first();

                RisultatoCandidaturaCollegioUninominaleCameraLista::unguard();
                RisultatoCandidaturaCollegioUninominaleCameraLista::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_lista_id' => $candidaturaLista->id,
                ], [
                    'voti' => $voti,
                ]);
            });
        });

        return Command::SUCCESS;
    }
}
