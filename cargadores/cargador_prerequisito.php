<?php

function procesar_prerequisitos() {
    $inputFile = "cargadores/originales/prerequisitos.csv";
    $outputFile = "data/prerequisitos_buenas.csv";

    // Abrimos el archivo
    $prerequisitos = fopen($inputFile, "r");

    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (($line = fgets($prerequisitos)) !== FALSE) {
        $line = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $line); // Convertimos a UTF-8
        $datos = str_getcsv($line, ",");

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
            // Serializa los datos para poder eliminar duplicados
            $array_datos_buenos[] = serialize($datos);
        } else {
            $array_datos_malos[] = $datos; // Si no es válido, agregar al array de datos malos
        }
    }
    fclose($prerequisitos);

    // Eliminar duplicados en los datos buenos
    $array_datos_buenos = array_unique($array_datos_buenos);
    // Deserializa los datos de vuelta a su forma original
    $array_datos_buenos = array_map('unserialize', $array_datos_buenos);

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
    $archivo_malos = fopen("data_malo/prerequisitos_malas.csv", "w");
    if ($archivo_malos === FALSE) {
        die("Error opening the bad output file.");
    }

    fwrite($archivo_malos, "\xEF\xBB\xBF");

    foreach ($array_datos_malos as $prerequisitos_malas) {
        fputcsv($archivo_malos, $prerequisitos_malas);
    }
    fclose($archivo_malos);
}


?>