<?php

use App\Http\Controllers\CandidatiController;
use App\Http\Controllers\EsteroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\ListeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/circoscrizioni-camera', [GeoController::class, 'circoscrizioniCamera'])->name('circoscrizioni_camera');
Route::get('/circoscrizione-camera/{circoscrizione}', [GeoController::class, 'circoscrizioneCamera'])->name('circoscrizione_camera');
Route::get('/collegio-plurinominale-camera/{collegioPlurinominale}', [GeoController::class, 'collegioPlurinominaleCamera'])->name('collegio_plurinominale_camera');
Route::get('/collegio-uninominale-camera/{collegioUninominale}', [GeoController::class, 'collegioUninominaleCamera'])->name('collegio_uninominale_camera');

Route::get('/circoscrizioni-senato', [GeoController::class, 'circoscrizioniSenato'])->name('circoscrizioni_senato');
Route::get('/circoscrizione-senato/{circoscrizione}', [GeoController::class, 'circoscrizioneSenato'])->name('circoscrizione_senato');
Route::get('/collegio-plurinominale-senato/{collegioPlurinominale}', [GeoController::class, 'collegioPlurinominaleSenato'])->name('collegio_plurinominale_senato');
Route::get('/collegio-uninominale-senato/{collegioUninominale}', [GeoController::class, 'collegioUninominaleSenato'])->name('collegio_uninominale_senato');

Route::get('/comuni', [GeoController::class, 'comuni'])->name('comuni');
Route::get('/comune/{comune}', [GeoController::class, 'comune'])->name('comune');

Route::get('/elenco-liste', [ListeController::class, 'liste'])->name('liste');
Route::get('/lista/{lista}', [ListeController::class, 'lista'])->name('lista');
Route::get('/lista/{lista}/collegi-plurinominali-camera', [ListeController::class, 'listaPlurinominaliCamera'])->name('lista_collegi_plurinominali_camera');
Route::get('/lista/{lista}/collegi-uninominali-camera', [ListeController::class, 'listaUninominaliCamera'])->name('lista_collegi_uninominali_camera');
Route::get('/lista/{lista}/collegi-plurinominali-senato', [ListeController::class, 'listaPlurinominaliSenato'])->name('lista_collegi_plurinominali_senato');
Route::get('/lista/{lista}/collegi-uninominali-senato', [ListeController::class, 'listaUninominaliSenato'])->name('lista_collegi_uninominali_senato');
Route::get('/lista/{lista}/estero-camera', [ListeController::class, 'listaEsteroCamera'])->name('lista_estero_camera');
Route::get('/lista/{lista}/estero-senato', [ListeController::class, 'listaEsteroSenato'])->name('lista_estero_senato');

Route::get('/coalizioni', [ListeController::class, 'coalizioni'])->name('coalizioni');
Route::get('/coalizione/{coalizione}', [ListeController::class, 'coalizione'])->name('coalizione');
Route::get('/coalizione/{coalizione}/collegi-uninominali-camera', [ListeController::class, 'coalizioneUninominaliCamera'])->name('coalizione_collegi_uninominali_camera');
Route::get('/coalizione/{coalizione}/collegi-uninominali-senato', [ListeController::class, 'coalizioneUninominaliSenato'])->name('coalizione_collegi_uninominali_senato');

Route::get('/candidati', [CandidatiController::class, 'candidati'])->name('candidati');
Route::get('/candidato/{candidato}', [CandidatiController::class, 'candidato'])->name('candidato');

Route::get('/risultati-camera', [GeoController::class, 'camera'])->name('risultati_camera');
Route::get('/risultati-senato', [GeoController::class, 'senato'])->name('risultati_senato');

Route::get('/ripartizioni-estero', [EsteroController::class, 'ripartizioni'])->name('ripartizioni_estero');
Route::get('/ripartizioni-estero/{ripartizioneEstero}', [EsteroController::class, 'ripartizione'])->name('ripartizione_estero');

Route::get('/nazioni-estero', [EsteroController::class, 'nazioni'])->name('nazioni_estero');
Route::get('/nazioni-estero/{nazioneEstero}', [EsteroController::class, 'nazione'])->name('nazione_estero');

Route::post('/ricerca', [SearchController::class, 'search'])->name('ricerca');
