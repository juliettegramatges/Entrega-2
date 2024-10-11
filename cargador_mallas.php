<?php

function procesar_asignaturas() {
    $asignaturas = fopen("Malla.csv", "r");
    $archivo_buenos = fopen("mallas_buenas.csv", "w");
    $archivo_malos = fopen("mallas_malas.csv", "w");

    while (($datos = fgetcsv($asignaturas)) !== FALSE) {
        if (count($datos) < 4) {
            fputcsv($archivo_malos, $datos);
            continue;
        }

        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z1-9]+$/', $datos[0]) &&  // Plan solo letras mayúsculas
                     is_string($datos[1]) && preg_match('/^[A-Z1-9]+$/', $datos[1]) &&  // Asignatura solo letras mayúsculas
                     is_string($datos[2]) && preg_match('/^[A-Z0-9 ]+$/', $datos[2]) && // Asignatura ID con letras, números y espacios
                     ctype_digit($datos[3]); // Nivel solo números enteros

        if ($es_valido) {
            fputcsv($archivo_buenos, $datos);
        } else {
            fputcsv($archivo_malos, $datos);
        }
    }

    fclose($asignaturas);
    fclose($archivo_buenos);
    fclose($archivo_malos);
}

procesar_asignaturas();
?>