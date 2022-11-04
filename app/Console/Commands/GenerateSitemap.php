<?php

namespace App\Console\Commands;

use App\Models\Candidato;
use App\Models\CircoscrizioneCamera;
use App\Models\CircoscrizioneSenato;
use App\Models\CollegioPlurinominaleCamera;
use App\Models\CollegioPlurinominaleSenato;
use App\Models\CollegioUninominaleCamera;
use App\Models\CollegioUninominaleSenato;
use App\Models\Comune;
use App\Models\Lista;
use App\Models\RipartizioneEstero;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elezioni2022:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Sitemap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SitemapIndex::create()
            ->add('/sitemap-geo.xml')
            ->add('/sitemap-comuni.xml')
            ->add('/sitemap-liste.xml')
            ->add('/sitemap-candidati.xml')
            ->add('/sitemap-estero.xml')
            ->writeToFile(public_path('sitemap.xml'));

        $geoSitemap = Sitemap::create()
            ->add(route('circoscrizioni_camera'))
            ->add(route('risultati_camera'))
            ->add(route('circoscrizioni_senato'))
            ->add(route('risultati_senato'));

        $geoSitemap = CircoscrizioneCamera::all()->reduce(function ($geoSitemap, $circoscrizione) {
            return $geoSitemap->add(route('circoscrizione_camera', $circoscrizione));
        }, $geoSitemap);

        $geoSitemap = CircoscrizioneSenato::all()->reduce(function ($geoSitemap, $circoscrizione) {
            return $geoSitemap->add(route('circoscrizione_senato', $circoscrizione));
        }, $geoSitemap);

        $geoSitemap = CollegioPlurinominaleCamera::all()->reduce(function ($geoSitemap, $collegio) {
            return $geoSitemap->add(route('collegio_plurinominale_camera', $collegio));
        }, $geoSitemap);

        $geoSitemap = CollegioPlurinominaleSenato::all()->reduce(function ($geoSitemap, $collegio) {
            return $geoSitemap->add(route('collegio_plurinominale_senato', $collegio));
        }, $geoSitemap);

        $geoSitemap = CollegioUninominaleCamera::all()->reduce(function ($geoSitemap, $collegio) {
            return $geoSitemap->add(route('collegio_uninominale_camera', $collegio));
        }, $geoSitemap);

        $geoSitemap = CollegioUninominaleSenato::all()->reduce(function ($geoSitemap, $collegio) {
            return $geoSitemap->add(route('collegio_uninominale_senato', $collegio));
        }, $geoSitemap);

        $geoSitemap->writeToFile(public_path('sitemap-geo.xml'));

        $comuniSitemap = Sitemap::create()
            ->add(route('comuni'));

        $comuniSitemap = Comune::all()->reduce(function ($comuniSitemap, $comune) {
            return $comuniSitemap->add(route('comune', $comune));
        }, $comuniSitemap);

        $comuniSitemap->writeToFile(public_path('sitemap-comuni.xml'));

        $listeSitemap = Sitemap::create()
            ->add(route('liste'));

        $listeSitemap = Lista::all()->reduce(function ($listeSitemap, $lista) {
            return $listeSitemap->add(route('lista', $lista))
                ->add(route('lista_collegi_plurinominali_camera', $lista))
                ->add(route('lista_collegi_plurinominali_senato', $lista))
                ->add(route('lista_collegi_uninominali_camera', $lista))
                ->add(route('lista_collegi_uninominali_senato', $lista));
        }, $listeSitemap);

        $listeSitemap->writeToFile(public_path('sitemap-liste.xml'));

        $candidatiSitemap = Sitemap::create()
            ->add(route('candidati'));

        $candidatiSitemap = Candidato::all()->reduce(function ($candidatiSitemap, $candidato) {
            return $candidatiSitemap->add(route('candidato', $candidato));
        }, $candidatiSitemap);

        $candidatiSitemap->writeToFile(public_path('sitemap-candidati.xml'));

        $esteroSitemap = Sitemap::create()
            ->add(route('ripartizioni_estero'));

        $esteroSitemap = RipartizioneEstero::all()->reduce(function ($esteroSitemap, $ripartizione) {
            $esteroSitemap->add(route('ripartizione_estero', $ripartizione));
            return $ripartizione->nazioni->reduce(function ($esteroSitemap, $nazione) {
                return $esteroSitemap->add(route('nazione_estero', $nazione));
            }, $esteroSitemap);
        }, $esteroSitemap);

        $esteroSitemap->writeToFile(public_path('sitemap-estero.xml'));

        return Command::SUCCESS;
    }
}
