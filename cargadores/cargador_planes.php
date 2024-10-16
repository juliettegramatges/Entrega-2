<?php

function validar_archivo_planes() {
    $planes = fopen("cargadores/originales/Planes.csv", "r"); // Abrir archivo en modo lectura
    stream_filter_append($planes, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_planes_buenos = fopen("data/planes_buenas.csv", "w"); // Abrir archivo de buenos en modo escritura
    $archivo_planes_malos = fopen("data_malo/planes_malas.csv", "w"); // Abrir archivo de malos en modo escritura

    // Escribir encabezados en el archivo de buenos
    $encabezados = ["Código Plan", "Facultad,Carrera", "Plan" ," Jornada", "Sede", "Grado", "Modalidad", "Inicio Vigencia"];
    fputcsv($archivo_planes_buenos, $encabezados);

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
                        in_array($datos[6], $grados_valios)
    
                    );



        if ($es_valido) {
            fputcsv($archivo_planes_buenos, $datos);
        } else {
            fputcsv($archivo_planes_malos, $datos); 
        }
    }

    fclose($planes); 
    fclose($archivo_planes_buenos);
    fclose($archivo_planes_malos); 
}

// Llamar a la función
validar_archivo_planes();




//BORRAR

function obtenerValoresUnicos($nombreArchivo, $indiceColumna) {
    $valoresUnicos = []; // Array para almacenar valores únicos
    $archivo = fopen($nombreArchivo, "r"); // Abrir archivo en modo lectura

    if ($archivo !== false) {
        // Leer la primera línea (cabecera)
        fgets($archivo);

        while (($linea = fgets($archivo)) !== false) {
            $datos = explode(",", trim($linea)); // Dividir la línea por comas
            
            // Asegúrate de que el índice de la columna es válido
            if (isset($datos[$indiceColumna])) {
                $valor = trim($datos[$indiceColumna]); // Obtener el valor de la columna deseada
                
                // Agregar el valor al array solo si no está ya presente
                if (!in_array($valor, $valoresUnicos)) {
                    $valoresUnicos[] = $valor;
                }
            }
        }
        fclose($archivo); // Cerrar el archivo
    }

    return $valoresUnicos; // Retornar array de valores únicos
}

/*
// Ejemplo de uso
$nombreArchivo = "planes_buenas.csv"; // Nombre del archivo CSV
$indiceColumna = 0; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 1; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 2; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 3; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 4; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 5; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 6; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 7; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

$indiceColumna = 8; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);
print_r($valoresUnicos); // Imprimir los valores únicos

*/
?>