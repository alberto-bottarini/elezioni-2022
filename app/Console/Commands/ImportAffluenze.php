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
        $this->line('Importo dati CAMERA');

        $affluenzaFile = '/var/data/elezioni-politiche-2022/affluenza-risultati/dati/affluenza/affluenzaComuni_mf.csv';
        $affluenzaCsv = Reader::createFromPath($affluenzaFile, 'r');
        $affluenzaCsv->setHeaderOffset(0);

        $records = $affluenzaCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach ($records as $row) {
            $comune = Comune::firstWhere('codice_istat', $row['CODICE ISTAT']);

            ComuneAffluenza::unguard();
            ComuneAffluenza::updateOrCreate([
                'comune_id' => $comune->id,
            ], [
                'elettori' => $row['elettori'],
                'elettori_m' => $row['ele_m'],
                'elettori_f' => $row['ele_f'],
                'percentuale_12' => $row['%h12'],
                'voti_12' => $row['voti_h12'],
                'voti_12_m' => $row['votm_h12'],
                'voti_12_f' => $row['votf_h12'],
                'percentuale_19' => $row['%h19'],
                'voti_19' => $row['voti_h19'],
                'voti_19_m' => $row['votm_h19'],
                'voti_19_f' => $row['votf_h19'],
                'percentuale_23' => $row['%h23'],
                'voti_23' => $row['voti_h23'],
                'voti_23_m' => $row['votm_h23'],
                'voti_23_f' => $row['votf_h23'],
            ]);

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
