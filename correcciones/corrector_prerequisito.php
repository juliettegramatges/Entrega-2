<?php

function corregirPrerequisitos() {
    $inputFile = 'data_malo/prerequisitos_malas.csv';
    $outputFile = 'data/prerequisitos_buenas.csv';

    $inputHandle = fopen($inputFile, 'r');
    if ($inputHandle === false) {
        die("Error al abrir el archivo de entrada.");
    }

    $outputHandle = fopen($outputFile, 'a');
    if ($outputHandle === false) {
        fclose($inputHandle);
        die("Error al abrir el archivo de salida.");
    }

    $datos_corregidos = [];

    while (($line = fgets($inputHandle)) !== false) {

        $line = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $line); // Convertimos a UTF-8
        $datos = str_getcsv($line, ",");
    
        if (empty($datos)) {
            continue;
        } // Las tuplas vacías se ignoran

    

        //Cambiamos Ingreso por ingreso
        if ($datos[4] == 'Ingreso') {
            $datos[4] = 'ingreso';
        }
        if ($datos[5] == 'Ingreso') {
            $datos[5] = 'ingreso';
        }

        // Cambiamos Egreso por egreso
        if ($datos[4] == 'Egreso') {
            $datos[4] = 'egreso';
        }
        if ($datos[5] == 'Egreso') {
            $datos[5] = 'egreso';
        }

        // Cambiamos por fijar por vacío
        if ($datos[4] == 'por fijar') {
            $datos[4] = '';
        }
        if ($datos[5] == 'por fijar') {
            $datos[5] = '';
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


        // Si el dato es valido se guarda
        if ($es_valido) {
            $datos_corregidos[] = $datos;
        }
       
    }

    // Verificar si $datos_corregidos tiene datos
    if (empty($datos_corregidos)) {
        fclose($inputHandle);
        fclose($outputHandle);
        die("No hay datos corregidos para escribir en el archivo de salida.");
    }

    // Imprimir la lista de datos corregidos
    foreach ($datos_corregidos as $line) {
        fputcsv($outputHandle, $line);
    }

    fclose($inputHandle);
    fclose($outputHandle);
}

?>