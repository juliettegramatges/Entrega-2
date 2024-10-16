<?php

function corregir_asignaturas() {
    // Abrir archivos
    $archivo_buenos = fopen("data/asignaturas_buenas.csv", "a");
    $archivo_malos = fopen("data_malo/asignaturas_malas.csv", "r");
    stream_filter_append($archivo_buenos, 'convert.iconv.ISO-8859-1/UTF-8');

    // Inicializar el array para almacenar datos actualizados
    $datos_actualizados = [];

    while (($datos = fgetcsv($archivo_malos, 0, ";")) !== FALSE) { // Usar ';' como delimitador
        if (count($datos) < 4) {
            // Si hay menos de 4 columnas, guardar en archivos malos
            $datos_actualizados[] = $datos;
            continue;
        }

        // Limpiar la columna de datos[2]: eliminar comillas y reemplazar dobles espacios por uno solo
        $datos[2] = preg_replace('/\s+/', ' ', trim(str_replace('"', '', $datos[2])));
        // Cambiar en datos[2] todas las mayusculas por su versión en mayusculas
        $datos[2] = mb_strtoupper($datos[2]);


        // Validar los datos
        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z0-9]+$/', $datos[0]) &&  // Plan solo letras mayúsculas
                     is_string($datos[1]) && preg_match('/^[A-ZÁÉÍÓÚÑ0-9]+$/i', $datos[1]) &&  // Asignatura solo letras
                     is_string($datos[2]) && preg_match('/^[A-Z0-9 :()-ÁÉÍÓÚÑ]+$/', $datos[2]) && // Asignatura ID con letras, números, espacios y dos puntos
                     ctype_digit($datos[3]); // Nivel solo números enteros

        if ($es_valido) {
            // Escribir la fila en asignaturas buenas si es válida
            fwrite($archivo_buenos, implode(';', $datos) . "\n");
        } else {
            // Si no es válido, agregar a archivos malos
            $datos_actualizados[] = $datos;
        }
    }

    fclose($archivo_buenos);
    fclose($archivo_malos);

    // Reabrir archivo de malas asignaturas para escribir actualizados
    $archivo_malos = fopen("asignaturas_malas.csv", "w");
    foreach ($datos_actualizados as $datos) {
        fwrite($archivo_malos, implode(';', $datos) . "\n");
    }
    fclose($archivo_malos);
}
