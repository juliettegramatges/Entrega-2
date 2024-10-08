<?php

function procesar_asignaturas() {
    $asignaturas = fopen("Asignaturas.csv", "r"); // Abrir archivo en modo lectura
    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (!feof($asignaturas)) {
        $linea = fgets($asignaturas);
        if (trim($linea) == "") {
            continue; // Saltar líneas vacías
        }

        $datos = explode(",", trim($linea)); // Dividimos las columnas por comas

        // Validamos si el array tiene al menos 4 columnas
        if (count($datos) < 4) {
            $array_datos_malos[] = $datos; // Si no tiene 4 columnas, la consideramos inválida
            continue;
        }

        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z]+$/', $datos[0]) &&  // Plan solo letras mayúsculas
                    is_string($datos[1]) && preg_match('/^[A-Z]+$/', $datos[1]) &&  // Asignatura solo letras mayúsculas
                    is_string($datos[2]) && preg_match('/^[A-Z0-9 ]+$/', $datos[2]) && // Asignatura ID con letras, números y espacios
                    ctype_digit($datos[3]); // Nivel solo números enteros

        if ($es_valido) {
            $array_datos_buenos[] = $datos; // Si es válido, lo agregamos al array de buenos
        } else {
            $array_datos_malos[] = $datos; // Si no es válido, lo agregamos al array de malos
        }
    }
    fclose($asignaturas); 

    // Escribir los datos buenos en un nuevo archivo CSV
    $archivo_buenos = fopen("asignaturas_buenas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_buenos as $asignatura_buenas) {
        fputcsv($archivo_buenos, $asignatura_buenas); // Escribir cada línea como CSV
    }
    fclose($archivo_buenos); // Cerrar el archivo de buenos

    // Escribir los datos malos en otro archivo CSV
    $archivo_malos = fopen("asignaturas_malas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_malos as $asignatura_malas) {
        fputcsv($archivo_malos, $asignatura_malas); // Escribir cada línea como CSV
    }
    fclose($archivo_malos); // Cerrar el archivo de malos
}

// Llamar a la función
procesar_asignaturas();
?>
