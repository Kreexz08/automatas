<?php

namespace App\Http\Controllers;

use App\Service\AnalizadorLexicoService as ServiceAnalizadorLexicoService;
use Illuminate\Http\Request;


class AnalizadorController extends Controller
{
    protected $analizadorService;

    public function __construct(ServiceAnalizadorLexicoService $analizadorService)
    {
        $this->analizadorService = $analizadorService;
    }

    public function index()
    {
        $errores = session('errores');
        return view('index', compact('errores'));
    }

    public function analizar(Request $request)
    {
        $resultados = $this->analizadorService->analizarCodigo($request->codigo);

        if (!empty($resultados['errores'])) {
            return view('index', ['errores' => $resultados['errores'], 'codigo' => $request->codigo]);
        }

        $this->analizadorService->guardarResultados($resultados);

        return redirect()->route('resultados');
    }

    public function resultados()
    {
        $variables = session('variables');
        $errores = session('errores');

        return view('resultados', compact('variables', 'errores'));
    }
}
