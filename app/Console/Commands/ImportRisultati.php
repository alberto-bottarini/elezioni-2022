<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Comune;
use App\Models\Lista;
use App\Models\RisultatoCandidaturaCollegioUninominaleCamera;
use App\Models\RisultatoCandidaturaCollegioUninominaleCameraLista;
use App\Models\RisultatiCandidaturaCollegioUninominaleSenato;
use App\Models\RisultatiCandidaturaCollegioUninominaleSenatoLista;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportRisultati extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-risultati {--palazzo=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Risultati';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $provinceMap = array_flip([
            'AG' => 'AGRIGENTO',
            'AL' => 'ALESSANDRIA',
            'AN' => 'ANCONA',
            'AO' => 'AOSTA',
            'AQ' => 'L\'AQUILA',
            'AR' => 'AREZZO',
            'AP' => 'ASCOLI PICENO',
            'AT' => 'ASTI',
            'AV' => 'AVELLINO',
            'BA' => 'BARI',
            'BT' => 'BARLETTA-ANDRIA-TRANI',
            'BL' => 'BELLUNO',
            'BN' => 'BENEVENTO',
            'BG' => 'BERGAMO',
            'BI' => 'BIELLA',
            'BO' => 'BOLOGNA',
            'BZ' => 'BOLZANO',
            'BS' => 'BRESCIA',
            'BR' => 'BRINDISI',
            'CA' => 'CAGLIARI',
            'CL' => 'CALTANISSETTA',
            'CB' => 'CAMPOBASSO',
            'SU' => 'SUD SARDEGNA',
            'CE' => 'CASERTA',
            'CT' => 'CATANIA',
            'CZ' => 'CATANZARO',
            'CH' => 'CHIETI',
            'CO' => 'COMO',
            'CS' => 'COSENZA',
            'CR' => 'CREMONA',
            'KR' => 'CROTONE',
            'CN' => 'CUNEO',
            'EN' => 'ENNA',
            'FM' => 'FERMO',
            'FE' => 'FERRARA',
            'FI' => 'FIRENZE',
            'FG' => 'FOGGIA',
            'FC' => 'FORLI\'-CESENA',
            'FR' => 'FROSINONE',
            'GE' => 'GENOVA',
            'GO' => 'GORIZIA',
            'GR' => 'GROSSETO',
            'IM' => 'IMPERIA',
            'IS' => 'ISERNIA',
            'SP' => 'LA SPEZIA',
            'LT' => 'LATINA',
            'LE' => 'LECCE',
            'LC' => 'LECCO',
            'LI' => 'LIVORNO',
            'LO' => 'LODI',
            'LU' => 'LUCCA',
            'MC' => 'MACERATA',
            'MN' => 'MANTOVA',
            'MS' => 'MASSA-CARRARA',
            'MT' => 'MATERA',
            'VS' => 'MEDIO CAMPIDANO',
            'ME' => 'MESSINA',
            'MI' => 'MILANO',
            'MO' => 'MODENA',
            'MB' => 'MONZA E DELLA BRIANZA',
            'NA' => 'NAPOLI',
            'NO' => 'NOVARA',
            'NU' => 'NUORO',
            'OG' => 'OGLIASTRA',
            'OT' => 'OLBIA TEMPIO',
            'OR' => 'ORISTANO',
            'PD' => 'PADOVA',
            'PA' => 'PALERMO',
            'PR' => 'PARMA',
            'PV' => 'PAVIA',
            'PG' => 'PERUGIA',
            'PU' => 'PESARO E URBINO',
            'PE' => 'PESCARA',
            'PC' => 'PIACENZA',
            'PI' => 'PISA',
            'PT' => 'PISTOIA',
            'PN' => 'PORDENONE',
            'PZ' => 'POTENZA',
            'PO' => 'PRATO',
            'RG' => 'RAGUSA',
            'RA' => 'RAVENNA',
            'RC' => 'REGGIO CALABRIA',
            'RE' => 'REGGIO NELL\' EMILIA',
            'RI' => 'RIETI',
            'RN' => 'RIMINI',
            'RM' => 'ROMA',
            'RO' => 'ROVIGO',
            'SA' => 'SALERNO',
            'SS' => 'SASSARI',
            'SV' => 'SAVONA',
            'SI' => 'SIENA',
            'SR' => 'SIRACUSA',
            'SO' => 'SONDRIO',
            'TA' => 'TARANTO',
            'TE' => 'TERAMO',
            'TR' => 'TERNI',
            'TO' => 'TORINO',
            'TP' => 'TRAPANI',
            'TN' => 'TRENTO',
            'TV' => 'TREVISO',
            'TS' => 'TRIESTE',
            'UD' => 'UDINE',
            'VA' => 'VARESE',
            'VE' => 'VENEZIA',
            'VB' => 'VERBANO-CUSIO-OSSOLA',
            'VC' => 'VERCELLI',
            'VR' => 'VERONA',
            'VV' => 'VIBO VALENTIA',
            'VI' => 'VICENZA',
            'VT' => 'VITERBO',
        ]);

        if ($this->option('palazzo') == 'camera') {
            $palazzi = ['camera'];
        } elseif ($this->option('palazzo') == 'camera') {
            $palazzi = ['senato'];
        } else {
            $palazzi = ['camera', 'senato'];
        }

        foreach ($palazzi as $palazzo) {
            $this->line('Importo risultati ' . $palazzo);

            $comuniFile = '/var/eligendo/siracusa.csv';
            $comuniCsv = Reader::createFromPath($comuniFile, 'r');
            $comuniCsv->setHeaderOffset(0);
            $comuniCsv->setDelimiter(';');

            $records = $comuniCsv->getRecords();

            if ($palazzo == 'camera') {
                $collegioUninominaleModel = CollegioUninominaleCamera::class;
                $collegioPlurinominale = CollegioPlurinominaleCamera::class;
                $risultatiCandidaturaCollegioUninominale = RisultatoCandidaturaCollegioUninominaleCamera::class;
                $risultatiCandidaturaCollegioUninominaleLista = RisultatoCandidaturaCollegioUninominaleCameraLista::class;
            } else {
                $collegioUninominaleModel = CollegioUninominaleSenato::class;
                $collegioPlurinominale = CollegioPlurinominaleSenato::class;
                $risultatiCandidaturaCollegioUninominale = RisultatiCandidaturaCollegioUninominaleSenato::class;
                $risultatiCandidaturaCollegioUninominaleLista = RisultatiCandidaturaCollegioUninominaleSenatoLista::class;
            }

            $nomiMapping = [
                'EMANUELA DETTA MILU\' ALLEGRA' => 'EMANUELA ALLEGRA DETTA MILU\'',
                'MARIA DETTA MARIELLA MOCCIA' => 'MARIA MOCCIA DETTA MARIELLA',
                'ANIELLO DETTO NELLO FIERRO' => 'ANIELLO FIERRO DETTO NELLO',
                'LORENZO MICHELE DETTO LAMBRENEDETTO LAMBRUGHI' => 'LORENZO MICHELE LAMBRUGHI DETTO LAMBRENEDETTO',
                'GIAN MARIA MADDALENA DETTA GIOVANNA MAGNI' => 'GIAN MARIA MADDALENA MAGNI DETTA GIOVANNA',
                'GIACOMO DIEGO DETTO GIANDIEGO GATTA' => 'GIACOMO DIEGO GATTA DETTO GIANDIEGO',
                'GENNARO DETTO THIAGO NENNA' => 'GENNARO NENNA DETTO THIAGO',
                'ALESSANDRINA DETTA SANDRA LONARDO MASTELLA' => 'ALESSANDRINA LONARDO MASTELLA DETTA SANDRA',
                'FRANCESCA DETTA LILIANA TROVATO' => 'FRANCESCA TROVATO DETTA LILIANA',
                'ALESSANDRO DETTO SANDRO RUOTOLO' => 'ALESSANDRO RUOTOLO DETTO SANDRO',
                'GIANLUIGI DETTO GIGI FARIOLI' => 'GIANLUIGI FARIOLI DETTO GIGI',
                'ARISTIDE DETTO DINO GRECO CASOTTI' => 'ARISTIDE GRECO CASOTTI DETTO DINO',
                'ACHILLE DETTO PIER LANFRANCHI' => 'ACHILLE LANFRANCHI DETTO PIER',
                'MARIA ROSARIA DETTA IAIA PERRELLI' => 'MARIA ROSARIA PERRELLI DETTA IAIA',
                'GIUSEPPE DETTO TIZIANO QUAINI' => 'GIUSEPPE QUAINI DETTO TIZIANO',
                'GIUSEPPINA MARIA DETTA GIUSI VERCESI' => 'GIUSEPPINA MARIA VERCESI DETTA GIUSI',
                'MARIA DETTA MIA GANDINI' => 'MARIA GANDINI DETTA MIA',
                'LISETTA DETTA LUISA CIAMBELLA' => 'LISETTA CIAMBELLA DETTA LUISA',
                'MASSIMO DETTO STROMBERG RANUCCI' => 'MASSIMO RANUCCI DETTO STROMBERG',
                'PATRIZIA DETTA PAT PRESTIPINO' => 'PATRIZIA PRESTIPINO DETTA PAT',
                'ROSALBA DETTA LINA GIANNINO' => 'ROSALBA GIANNINO DETTA LINA',
                'DOMENICO DETTO MIMMO CIRUZZI' => 'DOMENICO CIRUZZI DETTO MIMMO',
                'PRENESTE DETTO TITO ANZOLIN' => 'PRENESTE ANZOLIN DETTO TITO',
                'PIETRO DETTO PIERO BEVILACQUA' => 'PIETRO BEVILACQUA DETTO PIERO',
                'CALOGERO DETTO GERY BAVETTA' => 'CALOGERO BAVETTA DETTO GERY',
                'GIUSEPPE DETTO OSCURATO CIRILLO' => 'GIUSEPPE CIRILLO DETTO OSCURATO',
            ];

            $bar = $this->output->createProgressBar(iterator_count($records));
            $bar->setFormat('very_verbose');
            $bar->start();

            foreach ($records as $record) {
                $nomeCollegio = $record['COLLEGIO UNINOMINALE'];
                $nomeCollegioPluri = $record['COLLEGIO PLURINOMINALE'];
                $nomeLista = trim($record['LISTA']);
                $nome = trim($record['NOME']);
                $nome = str_replace('  ', ' ', $nome);
                $cognome = trim($record['COGNOME']);
                $cognome = str_replace('  ', ' ', $cognome);

                $nomeCompleto = $nome . ' ' . $cognome;

                if (isset($nomiMapping[$nomeCompleto])) {
                    $nomeCompleto = $nomiMapping[$nomeCompleto];
                }

                $collegioUninominale = $collegioUninominaleModel::firstWhere('nome', $nomeCollegio);
                $collegioPlurinominale = $collegioPlurinominale::firstWhere('nome', $nomeCollegioPluri);

                $candidato = Candidato::where('nome', $nomeCompleto)
                    ->first();

                $lista = Lista::firstWhere('nome', $nomeLista);

                if (!$collegioUninominale) {
                    $this->error('Collegio uninominale ' . $palazzo . ' non trovato ' . $nomeCollegio);
                    continue;
                }

                if (!$collegioPlurinominale) {
                    $this->error('Collegio plurinominale ' . $palazzo . ' non trovato ' . $nomeCollegioPluri);
                    continue;
                }

                if (!$candidato) {
                    $this->error('Candidato non trovato ' . $nome . ' ' . $cognome);
                    continue;
                }

                if (!$lista) {
                    $this->error('Lista non trovato ' . $nomeLista);
                    continue;
                }

                if (!isset($provinceMap[$record['PROVINCIA']])) {
                    $this->error('Provincia ' . $record['PROVINCIA'] . ' non trovata');
                    continue;
                }

                if ($record['COMUNE'] == 'CALLIANO' && $record['PROVINCIA'] == 'ASTI') {
                    $record['COMUNE'] = 'CALLIANO MONFERRATO';
                }

                $provincia = $provinceMap[$record['PROVINCIA']];
                $comune = Comune::where('nome', $record['COMUNE'])
                    ->where('provincia', $provincia)
                    ->first();

                if (!$comune) {
                    $this->error('Comune ' . $record['COMUNE'] . ' (' . $provincia . ') non trovato');
                    continue;
                }

                $candidatura = $collegioUninominale->candidature()->firstWhere('candidato_id', $candidato->id);
                if (!$candidatura) {
                    $this->error('Candidatura uninominale ' . $palazzo . ' non trovata per ' . $nomeCompleto . ' in ' . $collegioUninominale->nome);
                    continue;
                }

                $candidaturaLista = $candidatura->candidatureLista()->firstWhere('lista_id', $lista->id);
                if (!$candidaturaLista) {
                    $this->error('Candidatura uninominale lista ' . $palazzo . ' non trovata per ' . $lista->nome . ' in ' . $collegioUninominale->nome);
                    continue;
                }

                $risultatiCandidaturaCollegioUninominale::unguard();
                $risultatiCandidaturaCollegioUninominale::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_id' => $candidatura->id,
                ], [
                    'voti_candidato' => $record['VOTI SOLO CANDIDATO'],
                    'voti' => $record['VOTI CANDIDATO'],
                ]);

                $risultatiCandidaturaCollegioUninominaleLista::unguard();
                $risultatiCandidaturaCollegioUninominaleLista::updateOrCreate([
                    'comune_id' => $comune->id,
                    'candidatura_lista_id' => $candidaturaLista->id,
                ], [
                    'voti' => $record['VOTI LISTE'],
                ]);

                $bar->advance();
            }

            $this->newLine();
        }

        return Command::SUCCESS;
    }
}
