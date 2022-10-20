<?php

namespace App\Console\Commands;

use App\Models\Comune;
use App\Models\ComuneAffluenza;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportAffluenze extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:import-affluenze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Affluenze';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Importo dati AFFLUENZE');

        $affluenzaFile = '/var/eligendo/Politiche2022_Votanti_Varie_Ore_Camera_Italia.csv';
        $affluenzaCsv = Reader::createFromPath($affluenzaFile, 'r');
        $affluenzaCsv->setHeaderOffset(0);
        $affluenzaCsv->setDelimiter(';');

        $records = $affluenzaCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

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

        foreach ($records as $row) {
            if ($row['COMUNE'] == 'CALLIANO' && $row['PROVINCIA'] == 'ASTI') {
                $row['COMUNE'] = 'CALLIANO MONFERRATO';
            }

            if (!isset($provinceMap[$row['PROVINCIA']])) {
                $this->error('Provincia ' . $row['PROVINCIA'] . ' non trovata');
                continue;
            }

            $provincia = $provinceMap[$row['PROVINCIA']];
            $comune = Comune::where('nome', $row['COMUNE'])
                ->where('provincia', $provincia)
                ->first();

            if (!$comune) {
                $this->error('Comune ' . $row['COMUNE'] . ' (' . $provincia . ') non trovato');
                continue;
            }

            ComuneAffluenza::unguard();
            ComuneAffluenza::updateOrCreate([
                'comune_id' => $comune->id,
            ], [
                'elettori' => $row['Elettori'],
                'voti_12' => $row['Ore 12'],
                'voti_19' => $row['Ore 19'],
                'voti' => $row['Ore 23'],
            ]);

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
