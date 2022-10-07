<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\Comune;
use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;

class GenerateOg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:generate-og {--all} {--candidati} {--comuni}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OG image for models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('all') || $this->option('candidati')) {
            $this->info('Genero OG per i candidati');
            $bar = $this->output->createProgressBar(Candidato::count());
            $bar->start();

            $logo = Image::make(public_path('logo.png'));
            $logo->resize(130, 130);

            Candidato::each(function ($candidato) use ($bar, $logo) {
                $l = strlen($candidato->nomeCompleto);
                $img = Image::canvas(1200, 630, '#075985');
                $img->insert($logo, 'top', 0, 60);
                $img->text('Scopri le candidature e i risultati di ', 600, 330, function ($font) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size(50);
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->text($candidato->nomeCompleto, 600, 430, function ($font) use ($l) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size($this->getSizeByLength($l));
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->text('su Elezioniamo 2022', 600, 500, function ($font) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size(50);
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->save(public_path('og/candidato/' . $candidato->id . '.png'));

                $bar->advance();
            });

            $bar->finish();
        }

        if ($this->option('all') || $this->option('comuni')) {
            $this->info('Genero OG per i comuni');
            $bar = $this->output->createProgressBar(Comune::count());
            $bar->start();

            $logo = Image::make(public_path('logo.png'));
            $logo->resize(130, 130);

            Comune::each(function ($comune) use ($bar, $logo) {
                $l = strlen($comune->nomeCompleto);
                $img = Image::canvas(1200, 630, '#075985');
                $img->insert($logo, 'top', 0, 60);
                $img->text('Scopri le candidature e i risultati a ', 600, 330, function ($font) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size(50);
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->text($comune->nomeCompleto, 600, 430, function ($font) use ($l) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size($this->getSizeByLength($l));
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->text('su Elezioniamo 2022', 600, 500, function ($font) {
                    $font->file(resource_path('fonts/Lato-Regular.ttf'));
                    $font->size(50);
                    $font->color('#fdf6e3');
                    $font->align('center');
                });
                $img->save(public_path('og/comune/' . $comune->id . '.png'));

                $bar->advance();
            });

            $bar->finish();
        }
    }

    private function getSizeByLength($l)
    {
        if ($l <= 18) {
            return 100;
        }

        if ($l <= 28) {
            return 80;
        }

        if ($l <= 35) {
            return 60;
        }

        return 50;
    }
}
