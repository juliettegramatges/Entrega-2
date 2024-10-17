<?php

function procesar_docentes_planificados() {
    $docentes = fopen("cargadores/originales/docentes planificados.csv", "r");
    stream_filter_append($docentes, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_buenos = fopen("data/docentes planificados_buenos.csv", "w");
    $archivo_malos = fopen("data_malo/docentes planificados_malos.csv", "w");

    // Array para almacenar los identificadores únicos (usando el campo del docente, asumiendo que es el primer campo)
    $docentes_unicos = [];

    while (($datos = fgetcsv($docentes)) !== FALSE) {
        if (count($datos) < 16) {
            fputcsv($archivo_malos, $datos);
            continue;
        }

        $es_valido = ctype_digit($datos[0]) && strlen($datos[0]) == 8 && // Solo enteros de largo 8
                    is_string($datos[1]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[1]) && // Solo texto
                    is_string($datos[2]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[2]) && // Solo texto
                    ctype_digit($datos[3]) &&
                    is_string($datos[4]) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $datos[4]) &&
                    is_string($datos[5]) && preg_match('/^[a-zA-Z0-9._%+-]+@lamejor\.com$/', $datos[5]) &&
                    (ctype_digit($datos[6]) && $datos[6] <= 40 || $datos[6] == '') &&
                    ($datos[7] == 'HONORARIO' || $datos[7] == 'FULL TIME' || $datos[7] == 'PART TIME') &&
                    ($datos[8] == 'diurno' || $datos[8] == '') &&
                    ($datos[9] == 'vespertino' || $datos[9] == '') &&
                    ($datos[10] == 'HOGWARTS' || $datos[10] == 'BEAUXBATON' || $datos[10] == 'UAGADOU') &&
                    is_string($datos[11]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[11]) &&
                    ($datos[12] == 'LICENCIATURA' || $datos[12] == 'MAGISTER' || $datos[12] == 'DOCTOR') &&
                    ($datos[13] == 'ASISTENTE DOCENTE' || $datos[13] == 'ASOCIADO DOCENTE' || $datos[13] == 'INSTRUCTOR DOCENTE' || 
                     $datos[13] == 'ASOCIADA DOCENTE' || $datos[13] == 'INSTRUCTORA DOCENTE' || $datos[13] == 'TITULAR DOCENTE' || 
                     $datos[13] == 'ASISTENTE REGULAR' || $datos[13] == 'ASOCIADO REGULAR' || 
                     $datos[13] == 'INSTRUCTOR REGULAR' || $datos[13] == 'ASOCIADA REGULAR' || 
                     $datos[13] == 'INSTRUCTORA REGULAR' || $datos[13] == 'TITULAR REGULAR') &&
                    is_string($datos[14]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[14]) &&
                    ($datos[15] == 'Administrativo' || $datos[15] == 'Académico' || $datos[15] == '');

        if ($es_valido) {
            // Supongamos que el primer campo (datos[0]) es el identificador único del docente
            $id_docente = $datos[0];

            // Verificar si el docente ya ha sido agregado
            if (!in_array($id_docente, $docentes_unicos)) {
                // Agregar a la lista de docentes únicos
                $docentes_unicos[] = $id_docente;

                // Escribir la fila en el archivo de buenos
                fwrite($archivo_buenos, implode(';', $datos) . "\n");
            }
        } else {
            fwrite($archivo_malos, implode(';', $datos) . "\n");
        }
    }

    fclose($docentes);
    fclose($archivo_buenos);
    fclose($archivo_malos);
}
?>
