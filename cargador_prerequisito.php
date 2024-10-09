<?php

function procesar_prerequisitos() {

    $prerequisitos = fopen("prerequisitos.csv", "r"); // Abrir archivo en modo lectura
    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (($datos = fgetcsv($prerequisitos, 1000, ",")) !== FALSE) {
        
        if (empty($datos)) {
            continue; // Saltar líneas vacías
        }

        // Validamos si el array tiene al menos 5 columnas (Puede tener 5 o 6 columnas)
        if (count($datos) < 5) {
            $array_datos_malos[] = $datos; // Si no tiene 5 columnas, la consideramos inválida
            continue;
        }

        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z0-9]+$/', $datos[0]) &&  // Plan solo letras mayúsculas y números
                    is_string($datos[1]) && preg_match('/^[A-Z0-9]+$/', $datos[1]) &&  // Asignatura solo letras mayúsculas y números
                    ctype_digit($datos[3]); // Nivel solo números enteros

        if ($es_valido) {
            $array_datos_buenos[] = $datos; // Si es válido, lo agregamos al array de buenos
        } else {
            $array_datos_malos[] = $datos; // Si no es válido, lo agregamos al array de malos
        }
    }
    fclose($prerequisitos); 

    // Escribir los datos buenos en un nuevo archivo CSV
    $archivo_buenos = fopen("prerequisitos_buenas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_buenos as $prerequisitos_buenas) {
        fputcsv($archivo_buenos, $prerequisitos_buenas); // Escribir cada línea como CSV
    }
    fclose($archivo_buenos); // Cerrar el archivo de buenos

    // Escribir los datos malos en otro archivo CSV
    $archivo_malos = fopen("prerequisitos_malas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_malos as $prerequisitos_malas) {
        fputcsv($archivo_malos, $prerequisitos_malas); // Escribir cada línea como CSV
    }
    fclose($archivo_malos); // Cerrar el archivo de malos
}

// Llamar a la función
procesar_prerequisitos();

?>
