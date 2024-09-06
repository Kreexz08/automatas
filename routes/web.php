<?php use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalizadorController;




// Rutas del controlador
Route::get('/', [AnalizadorController::class, 'index']);

Route::post('/analizar', [AnalizadorController::class, 'analizar'])->name('analizar');

Route::get('/resultados', [AnalizadorController::class, 'resultados'])->name('resultados');

// etc...
