<?php
function validar_archivo_planes() {
    $planes = fopen("cargadores/originales/Planes.csv", "r"); // Abrir archivo en modo lectura
    stream_filter_append($planes, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_planes_buenos = fopen("data/planes_buenas.csv", "w"); // Abrir archivo de buenos en modo escritura
    $archivo_planes_malos = fopen("data_malo/planes_malas.csv", "w"); // Abrir archivo de malos en modo escritura

    // Conjunto para almacenar filas únicas
    $filas_unicas = [];

    while (!feof($planes)) {
        $linea = fgets($planes);
        if (trim($linea) == "") {
            continue; // Saltar líneas vacías
        }

        $datos = explode(",", trim($linea)); // Dividimos las columnas por comas

        //CASO CUANDO HAY MÁS DE UNA COLUMNA PORQUE EL NOMBRE DE LA ASIGNATURA TIENE UNA COMA
        $diccionario = [
            "ster en Mediaci" => "n Colaborativa de Conflictos"
        ];

        if (count($datos) > 8) {
            foreach ($diccionario as $clave => $valor) {
                if (strpos($datos[2], $clave) !== false && strpos($datos[3], $valor) !== false) {
                    $juntar = trim($datos[2] . " " . $datos[3]); 
                    $datos[2] = $juntar; 

                    $datos[3] = trim($datos[4]);
                    $datos[4] = trim($datos[5]);
                    $datos[5] = trim($datos[6]); 
                    $datos[6] = trim($datos[7]); 
                    $datos[7] = trim($datos[8]); 

                    array_splice($datos, 8, 1); 
                }
            }
        }

        $jornadas_validas = ["Vespertino", "Diurno"];
        $modalidades_validas = ["Presencial", "OnLine", "Híbrida", "Hibrida"]; //en todo caso no hay hibridas
        $sedes_validas = ["Uagadou", "Beauxbaton", "Hogwarts"];
        $grados_valios = ["Pregrado", "Postgrado", "Programa Especial"];

        $es_valido = (in_array($datos[4], $jornadas_validas) &&
                       in_array($datos[7], $modalidades_validas) &&
                       in_array($datos[5], $sedes_validas) &&
                       in_array($datos[6], $grados_valios));

        // Comprobar si la fila es válida y no está duplicada
        if ($es_valido) {
            // Convertimos la fila en una representación única (cadena)
            $fila_clave = implode(',', $datos);

            if (!in_array($fila_clave, $filas_unicas)) {
                fputcsv($archivo_planes_buenos, $datos);
                $filas_unicas[] = $fila_clave; // Agregar fila a conjunto de filas únicas
            }
        } else {
            fputcsv($archivo_planes_malos, $datos); 
        }
    }

    fclose($planes); 
    fclose($archivo_planes_buenos);
    fclose($archivo_planes_malos); 
}

?>