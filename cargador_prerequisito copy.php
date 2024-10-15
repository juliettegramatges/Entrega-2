<?php

function procesar_prerequisitos() {
    $inputFile = "prerequisitos.csv";
    $outputFile = "prerequisitos_buenas.csv";

    // Abrimos el archivo
    $prerequisitos = fopen($inputFile, "r");
    stream_filter_append($prerequisitos, 'convert.iconv.ISO-8859-1/UTF-8'); // Convertir la codificación a UTF-8

    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (($datos = fgetcsv($prerequisitos, 1000, ",")) !== FALSE) {

        if (empty($datos)) {
            continue; // Borrar tuplas vacías
        }

        // Verificar que la tupla tenga 5 o 6 columnas
        if (count($datos) < 5) {
            $array_datos_malos[] = $datos; 
            continue;
        }

        $es_valido =
                    // Plan solo tiene mayúsculas y números
                    is_string($datos[0]) && preg_match('/^[A-Z0-9]+$/', $datos[0]) &&
                    // Para la asignatura debe ser un string
                    is_string($datos[1]) && preg_match('/^[A-ZÁÉÍÓÚÑ0-9]+$/i', $datos[1]) &&  // Asignatura solo letras
                    // Nivel solo números enteros
                    ctype_digit($datos[3]) &&
                    // Prerequisitos pueden ser un entero de 4 dígitos, ingreso, egreso, B, L, B v L.
                    (ctype_digit($datos[4]) || in_array($datos[4], ['ingreso', 'egreso', 'B', 'L', 'B v L']) || empty($datos[4])) &&
                    // Prerequisitos pueden ser un entero de 4 dígitos, ingreso, egreso, B, L, B v L, o vacío.
                    (ctype_digit($datos[5]) || in_array($datos[5], ['ingreso', 'egreso', 'B', 'L', 'B v L']) || empty($datos[5])) ;


        // El código de la asignatura se guarda quitando el código de $datos[0]
        $datos[1] = substr($datos[1], 3);

        if ($es_valido) {
            $array_datos_buenos[] = $datos; // If valid, add to the good array
        } else {
            $array_datos_malos[] = $datos; // If not valid, add to the bad array
        }
    }
    fclose($prerequisitos);

    // Aguardamos archivos buenos
    $archivo_buenos = fopen($outputFile, "w");
    if ($archivo_buenos === FALSE) {
        die("Error opening the output file.");
    }

    fwrite($archivo_buenos, "\xEF\xBB\xBF");

    foreach ($array_datos_buenos as $prerequisitos_buenas) {
        fputcsv($archivo_buenos, $prerequisitos_buenas);
    }
    fclose($archivo_buenos);

    // Guardamos los archivos malos
    $archivo_malos = fopen("prerequisitos_malas.csv", "w");
    if ($archivo_malos === FALSE) {
        die("Error opening the bad output file.");
    }

    fwrite($archivo_malos, "\xEF\xBB\xBF");

    foreach ($array_datos_malos as $prerequisitos_malas) {
        fputcsv($archivo_malos, $prerequisitos_malas);
    }
    fclose($archivo_malos);

}

procesar_prerequisitos();
?>