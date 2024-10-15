<?php

function corregirPlaneacion() {
    $inputFile = 'planeacion_malas.csv';
    $outputFile = 'planeacion_corregidas.csv';

    $inputHandle = fopen($inputFile, 'r');
    if ($inputHandle === false) {
        die("Error al abrir el archivo de entrada.");
    }

    $outputHandle = fopen($outputFile, 'w');
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

        // Corrección de datos acá

        // Si un ramo tiene más inscritos que cupos, se aumenta el cupo
        if ($datos[11] > $datos[10]) {
            $datos[10] = $datos[11];
        }


        // Si profesor principal no es S, entonces RUN, Nombre Docente, 1er Apellido Docente, 2do Apellido Docente y Jerarquización se cambian a vacío
        if ($datos[19] != 'S') {
            $datos[20] = '';
            $datos[21] = '';
            $datos[22] = '';
            $datos[23] = '';
            $datos[24] = '';
        }

        // Si el 2do Apellido Docente es 0, se cambia a vacío
        if ($datos[23] == '0') {
            $datos[23] = '';
        }

        // Si el RUN es #N/A, 100, 6012 o 4010, se cambia a vacío junto con el Nombre Docente, 1er Apellido Docente y 2do Apellido Docente
        if ($datos[20] == '#N/A' || $datos[20] == '100' || $datos[20] == '6012' || $datos[20] == '4010') {
            $datos[20] = '';
            $datos[21] = 'Por fijar';
            $datos[22] = '';
            $datos[23] = '';
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
            // Ahora se admite vacío
            (in_array($datos[19], ['S']) || empty($datos[19]) ) &&
            // RUN: entero de 7 o 8 dígitos ACÁ SE CAEN COMO 430. MUCHOS POR NA Y ALGUNOS POR RUT 130 MILLONES
            (preg_match('/^\d{7,8}$/', $datos[20]) || empty($datos[20]) ) &&
            // Nombre Docente: texto de máximo 50 caracteres. Puede ser vacío
            (strlen($datos[21]) <= 50 || empty($datos[21]) ) &&
            // 1er Apellido Docente: texto de máximo 50 caracteres. Puede ser vacío
            (strlen($datos[22]) <= 50 || empty($datos[22]) ) && 
            // 2do Apellido Docente: texto de máximo 50 caracteres. No puede ser 0. ACÁ SE CAEN 152
            // Puede ser vacío
           ( strlen($datos[23]) <= 50 && preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/', $datos[23]) || empty($datos[23])) &&
            // Jerarquización: texto de máximo 5 caracteres o vacío
            (strlen($datos[24]) <= 5 || empty($datos[24]));

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

corregirPlaneacion();

echo "Proceso completado. Revisa el archivo para ver los resultados.";

?>