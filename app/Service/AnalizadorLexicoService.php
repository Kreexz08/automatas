<?php

namespace App\Service;
use Illuminate\Support\Facades\Session;

class AnalizadorLexicoService
{


    const PATRON_DECLARACION = '/^declare\s+(.*?)\s+(entero|real|cadena|logico|fecha);$/';

    const PATRON_IDENTIFICADOR = '/^[a-zA-Z][a-zA-Z0-9_]{0,14}$/';


    public function validarIdentificador($identificador)
    {

        $patron = '/^[a-zA-Z][a-zA-Z0-9_]{0,14}$/';

        return preg_match($patron, $identificador);
    }


    public function analizarCodigo($codigo)
    {
        $lineas = explode("\n", $codigo);

        $variables = [];
        $errores = [];

        foreach ($lineas as $numeroLinea => $linea) {
            $linea = trim($linea);

            // Validar si la línea está vacía
            if (empty($linea)) {
                $mensaje = "No ha enviado código para analizar";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si está vacía
            }

            // Validar si la línea contiene la palabra "declare"
            if (strpos($linea, 'declare') === false) {
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Falta la palabra 'declare' en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si no contiene "declare"
            }



            // validar si la linea contiene la palabra entero, real, cadena, logico o fecha
            if (strpos($linea, 'entero') === false && strpos($linea, 'real') === false && strpos($linea, 'cadena') === false && strpos($linea, 'logico') === false && strpos($linea, 'fecha') === false) {
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Falta el tipo de dato en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si no contiene "entero", "real", "cadena", "logico" o "fecha"
            }

             // Validar si la línea termina con punto y coma
             if (substr($linea, -1) !== ';') {
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Te falta el punto y coma al final en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si no tiene punto y coma
            }

            // validar si la linea tiene dos palabras declare
            if (substr_count($linea, 'declare') > 1) {
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Hay más de una palabra 'declare' en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si contiene más de una palabra "declare"
            }

            // validar si la linea tiene dos puntos y coma
            if (substr_count($linea, ';') > 1) {
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Hay más de un punto y coma en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
                continue; // Saltar el análisis de esta línea si contiene más de un punto y coma
            }



            // validar que la variable sea válida
            if (preg_match(self::PATRON_DECLARACION, $linea, $coincidencias)) {
                $listaVariables = explode(',', $coincidencias[1]);

                foreach ($listaVariables as $variable) {
                    $variable = trim($variable);

                    if ($this->validarIdentificador($variable)) {
                        // Variable válida
                        $variables[] = [
                            'nombre' => $variable,
                            'tipo' => $coincidencias[2],
                            'linea' => $numeroLinea + 1
                        ];
                    } else {
                        // Identificador inválido
                        $numeroLinea1 = $numeroLinea + 1;
                        $mensaje = "Identificador '$variable' inválido en línea $numeroLinea1";
                        $errores[] = ['error' => $mensaje, 'linea' => ''];
                    }
                }
            } else {
                // Declaración inválida
                $numeroLinea1 = $numeroLinea + 1;
                $mensaje = "Declaración inválida en la línea $numeroLinea1";
                $errores[] = ['error' => $mensaje, 'linea' => ''];
            }
        }

        return [
            'variables' => $variables,
            'errores' => $errores
        ];
    }


    public function guardarResultados($resultados)
    {
        Session::put('variables', $resultados['variables']);
        Session::put('errores', $resultados['errores']);
    }
}
