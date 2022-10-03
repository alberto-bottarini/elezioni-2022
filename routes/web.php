<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GeoController;
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


Route::get('/liste', [ListeController::class, 'liste'])->name('liste');
Route::get('/lista/{lista}', [ListeController::class, 'lista'])->name('lista');

Route::post('/ricerca', [SearchController::class, 'search'])->name('ricerca');
