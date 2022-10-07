<?php

namespace App\Console\Commands;

use App\Models\Comune;
use Illuminate\Console\Command;
use League\Csv\Reader;

class UpdateComuniIstat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:comuni-istat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update comuni ISTAT';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Importo codici comuni');

        $comuniFile = '/var/data/elezioni-politiche-2022/affluenza-risultati/risorse/anagraficaComuni.csv';
        $comuniCsv = Reader::createFromPath($comuniFile, 'r');
        $comuniCsv->setHeaderOffset(0);

        $records = $comuniCsv->getRecords();

        $bar = $this->output->createProgressBar(iterator_count($records));
        $bar->start();

        foreach ($records as $row) {
            if(
                $row['DESCRIZIONE COMUNE'] == 'CALLIANO' && $row['SIGLA'] == 'AT'
            ) {
                $row['DESCRIZIONE COMUNE'] = 'CALLIANO MONFERRATO';
            }

            $comune = Comune::where('nome', $row['DESCRIZIONE COMUNE'])
                ->where('provincia', $row['SIGLA'])
                ->get();

            if($comune->count() > 1) {
                $this->error('Comune duplicato ' . $row['DESCRIZIONE COMUNE'] .'(' . $row['SIGLA'] . ')');
            }

            if($comune->count() == 0) {
                $this->error('Comune no trovato ' . $row['DESCRIZIONE COMUNE'] .'(' . $row['SIGLA'] . ')');
            }

            $comune = $comune->first();
            $comune->codice_elettorale = $row['CODICE ELETTORALE'];
            $comune->codice_istat = $row['CODICE ISTAT'];
            $comune->codice_belfiore = $row['CODICE BELFIORE'];
            $comune->codice_interno = $row['codINT'];
            $comune->save();
            
            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
