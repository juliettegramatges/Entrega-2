<?php

function procesar_docentes_planificados() {
    $docentes = fopen("cargadores/originales/docentes planificados.csv", "r");
    stream_filter_append($docentes, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_buenos = fopen("data/docentes planificados_buenos.csv", "w");
    $archivo_malos = fopen("data_malo/docentes planificados_malos.csv", "w");

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
                    # me aseguro que sea un entero no mayor a 40, pero que pueda ser nulo
                    (ctype_digit($datos[6]) && $datos[6] <= 40 || $datos[6] == '') &&
                    # me aseeguro que solo sea HONORARIO, FULL TIME o PART TIME
                    ($datos[7] == 'HONORARIO' || $datos[7] == 'FULL TIME' || $datos[7] == 'PART TIME') &&
                    # me aseguro que diga diurno o este vacío
                    ($datos[8] == 'diurno' || $datos[8] == '') &&
                    ($datos[9] == 'vespertino' || $datos[9] == '');
                    # me aseguro que sea ”HOGWARTS”,”BEAUXBATON” o ”UAGADOU”
                    ($datos[10] == 'HOGWARTS' || $datos[10] == 'BEAUXBATON' || $datos[10] == 'UAGADOU') &&
                    is_string($datos[11]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[11]) &&
                    #me aseguro que sea ”LICENCIATURA”, ”MAGISTER” o ”DOCTOR”
                    ($datos[12] == 'LICENCIATURA' || $datos[12] == 'MAGISTER' || $datos[12] == 'DOCTOR') &&
                    # me aseguro que sea ”ASISTENTE”, ”ASOCIADO”, ”INSTRUCTOR” o ”TITULAR”, en su denotaci ́on masculina y femenina, combinado con ”DOCENTE”, o ”REGULAR”.
                    ($datos[13] == 'ASISTENTE DOCENTE' || $datos[13] == 'ASOCIADO DOCENTE' || $datos[13] == 'INSTRUCTOR DOCENTE' || $datos[13] == 'ASOCIADA DOCENTE' || 
                        $datos[13] == 'INSTRUCTORA DOCENTE' ||$datos[13] == 'TITULAR DOCENTE' || $datos[13] == 'ASISTENTE REGULAR' || $datos[13] == 'ASOCIADO REGULAR' || 
                        $datos[13] == 'INSTRUCTOR REGULAR' || $datos[13] == 'ASOCIADA REGULAR' || $datos[13] == 'INSTRUCTORA REGULAR' ||$datos[13] == 'TITULAR REGULAR' ) &&
                    is_string($datos[14]) && preg_match('/^[A-Za-zÁÉÍÓÚáéíóú]+(?: [A-Za-zÁÉÍÓÚáéíóú]+)*$/', $datos[14]);
                    # me aserguro que tenga ”administrativa” o ”académica” o estarvacio
                    ($datos[15] == 'Administrativo' || $datos[15] == 'Académico' || $datos[15] == '') ;
                    
        if ($es_valido) {
            fwrite($archivo_buenos, implode(';', $datos) . "\n");
        } else {
            fwrite($archivo_malos, implode(';', $datos) . "\n");
        }
    }

    #Falta sacar el " ", de varias columnas
    #Falta dejar bien escrito ESTAMENTO

    fclose($docentes);
    fclose($archivo_buenos);
    fclose($archivo_malos);
}
?>