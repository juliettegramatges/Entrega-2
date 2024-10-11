<?php

function procesar_planeacion() {

    $planeacion = fopen("Planeación.csv", "r"); // Abrir archivo en modo lectura
    stream_filter_append($planeacion, 'convert.iconv.ISO-8859-1/UTF-8'); // Convertir la codificación a UTF-8
    $array_datos_buenos = [];
    $array_datos_malos = [];

    while (($datos = fgetcsv($planeacion, 1000, ",")) !== FALSE) {
        
        if (empty($datos)) {
            continue; // Saltar líneas vacías
        }

        // Validamos si el array tiene al menos 25 columnas 
        if (count($datos) < 25) {
            $array_datos_malos[] = $datos; // Si no tiene 25 columnas, la consideramos inválida
            continue;
        }

        $es_valido = // Periodo: fecha de tipo YYYY-MM
            preg_match('/^\d{4}-\d{2}$/', $datos[0]) && 
            // Sede: texto de máximo 50 caracteres
            strlen($datos[1]) <= 50 &&
            // Facultad: texto de máximo 50 caracteres
            strlen($datos[2]) <= 50 &&
            // Código Depto: entero de 4 dígitos
            preg_match('/^\d{4}$/', $datos[3]) &&
            // Departamento: texto de máximo 50 caracteres
            strlen($datos[4]) <= 50 &&
            // Id Asignatura: string de 2 letras y 5 o 6 dígitos
            preg_match('/^[A-Z]{2}\d{5,6}$/', $datos[5]) &&
            // Asignatura: texto de máximo 100 caracteres
            strlen($datos[6]) <= 100 &&
            // Sección: entero de máximo 3 digitos
            preg_match('/^\d{1,3}$/', $datos[7]) &&
            // Duración: Puede ser I o S
            in_array($datos[8], ['I', 'S']) &&
            // Jornada: Puede ser Diurno o Vespertino
            in_array($datos[9], ['Diurno', 'Vespertino']) &&
            // Cupo: entero de máximo 3 dígitos
            preg_match('/^\d{1,3}$/', $datos[10]) &&
            // Inscritos: entero menor o igual a Cupo 2 FALLAN ACÁ
            $datos[11] <= $datos[10] &&
            // Día: puede ser lunes, martes, miércoles, jueves, viernes o sábado
            in_array($datos[12], ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado']) &&
            // Hora Inicio: hora de tipo HH:MM
            preg_match('/^\d{2}:\d{2}$/', $datos[13]) &&
            // Hora Fin: hora de tipo HH:MM, después de Hora Inicio
            $datos[14] > $datos[13] &&
            // Fecha Inicio: fecha de tipo DD/MM/YY
            preg_match('/^\d{2}\/\d{2}\/\d{2}$/', $datos[15]) &&
            // Fecha Fin: fecha de tipo DD/MM/YY, después de Fecha Inicio
            $datos[16] > $datos[15] &&
            // Lugar: texto de máximo 50 caracteres. no vacío
            strlen($datos[17]) <= 50 &&
            // Edificio: texto de máximo 50 caracteres
            strlen($datos[18]) <= 50 &&
            // Profesor Principal: S ACÁ SE CAEN 12
            in_array($datos[19], ['S']) &&
            // RUN: entero de 7 o 8 dígitos ACÁ SE CAEN COMO 430. MUCHOS POR NA Y ALGUNOS POR RUT 130 MILLONES
            preg_match('/^\d{7,8}$/', $datos[20]) &&
            // Nombre Docente: texto de máximo 50 caracteres
            strlen($datos[21]) <= 50 &&
            // 1er Apellido Docente: texto de máximo 50 caracteres
            strlen($datos[22]) <= 50 && 
            // 2do Apellido Docente: texto de máximo 50 caracteres. No puede ser 0. ACÁ SE CAEN 152
            // CREO Q HAY Q REEMPLAZAR LOS 0 POR NA
            strlen($datos[23]) <= 50 && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[23]) &&
            // Jerarquización: texto de máximo 5 caracteres
            strlen($datos[24]) <= 5;
            


        if ($es_valido) {
            $array_datos_buenos[] = $datos; // Si es válido, lo agregamos al array de buenos
        } else {
            $array_datos_malos[] = $datos; // Si no es válido, lo agregamos al array de malos
        }
    }
    fclose($planeacion); 

    // Escribir los datos buenos en un nuevo archivo CSV
    $archivo_buenos = fopen("planeacion_buenas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_buenos as $planeacion_buenas) {
        fputcsv($archivo_buenos, $planeacion_buenas); // Escribir cada línea como CSV
    }
    fclose($archivo_buenos); // Cerrar el archivo de buenos

    // Escribir los datos malos en otro archivo CSV
    $archivo_malos = fopen("planeacion_malas.csv", "w"); // Abrir archivo en modo escritura

    foreach ($array_datos_malos as $planeacion_malas) {
        fputcsv($archivo_malos, $planeacion_malas); // Escribir cada línea como CSV
    }
    fclose($archivo_malos); // Cerrar el archivo de malos
}

// Llamar a la función
procesar_planeacion();

?>
