<?php

function procesar_estudiantes() {

    $estudiantes = fopen("cargadores/originales/Estudiantes.csv", "r"); // Abrir archivo en modo lectura
    stream_filter_append($estudiantes, 'convert.iconv.ISO-8859-1/UTF-8'); // Convertir la codificación a UTF-8
    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (($datos = fgetcsv($estudiantes, 1000, ",")) !== FALSE) {
        
        if (empty($datos)) {
            continue; // Saltar líneas vacías
        }

        // Validamos si el array tiene al menos 5 columnas (Puede tener 5 o 6 columnas)
        if (count($datos) < 5) {
            $array_datos_malos[] = $datos; // Si no tiene 5 columnas, la consideramos inválida
            continue;
        }

        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z0-9]+$/', $datos[0]) &&  // Plan solo letras mayúsculas y números
                is_string($datos[1]) && preg_match('/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/', $datos[1]) &&  // Carrera solo letras y espacios
                preg_match('/^\d{4}\-\d{2}$/', $datos[2]) &&  // Cohorte corresponde a una fecha de tipo YYYY-MM
                preg_match('/^\d{6}$/', $datos[3]) &&  // Número de alumno solo números de 6 dígitos
                is_string($datos[4]) && preg_match('/^[NS]$/', $datos[4]) && // Bloqueo puede ser solo N o S
                // Causal Bloqueo: Si Bloque es N, debe ser Sin Bloqueo. Si bloque es S, puede ser cualquier cosa
                ($datos[4] == 'N' && $datos[5] == 'Sin Bloqueo' || $datos[4] == 'S' && is_string($datos[5])) &&
                // RUN: número entero de 7 o 8 dígitos
                is_numeric($datos[6]) && (strlen($datos[6]) == 7 || strlen($datos[6]) == 8) &&
                // DV: Número de 1 dígito o K
                (is_numeric($datos[7]) && strlen($datos[7]) == 1 || $datos[7] == 'K') &&
                // Primer nombre: solo letras y guiones
                is_string($datos[8]) && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[8]) &&
                // Segundo nombre: solo letras y puede ser vacío
                (is_string($datos[9]) && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[9]) || empty($datos[9])) &&
                // Apellido paterno: solo letras
                is_string($datos[10]) && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[10]) &&
                // Apellido materno: opcional
                (is_string($datos[11]) && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[11]) || empty($datos[11])) &&
                // Logro: string
                is_string($datos[12]) &&
                // Fecha logro: fecha en formato YYYY-MM
                preg_match('/^\d{4}\-\d{2}$/', $datos[13]) &&
                // Última carga: fecha en formato YYYY-MM o vacío
                (preg_match('/^\d{4}\-\d{2}$/', $datos[14]) || empty($datos[14]));

        if ($es_valido) {
            $array_datos_buenos[] = $datos; // Si es válido, lo agregamos al array de buenos
        } else {
            $array_datos_malos[] = $datos; // Si no es válido, lo agregamos al array de malos
        }
    }
    fclose($estudiantes); 

    // Escribir los datos buenos en un nuevo archivo CSV
    $archivo_buenos = fopen("data/estudiantes_buenas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_buenos as $estudiantes_buenas) {
        fputcsv($archivo_buenos, $estudiantes_buenas); // Escribir cada línea como CSV
    }
    fclose($archivo_buenos); // Cerrar el archivo de buenos

    // Escribir los datos malos en otro archivo CSV
    $archivo_malos = fopen("data_malo/estudiantes_malas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_malos as $estudiantes_malas) {
        fputcsv($archivo_malos, $estudiantes_malas); // Escribir cada línea como CSV
    }
    fclose($archivo_malos); // Cerrar el archivo de malos
}

// Llamar a la función
procesar_estudiantes();

?>