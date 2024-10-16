<?php
ini_set('memory_limit', '512M'); 

function validar_archivo_notas() {
    $notas = fopen("cargadores/originales/Notas.csv", "r"); // Abrir archivo en modo lectura
    stream_filter_append($notas, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_notas_buenas = fopen("data/notas_buenas.csv", "w"); // Abrir archivo de buenos en modo escritura
    $archivo_notas_malas = fopen("data_malo/notas_malas.csv", "w"); // Abrir archivo de malos en modo escritura


     // Escribir encabezados en el archivo de buenos
     $encabezados = ["Código Plan", "Plan", "Cohorte", "Sede", "RUN" , "DV", "Nombres", "Apellido Paterno", "Apellido Materno", "Número de alumno", "Periodo Asignatura", "Código Asignatura", "Asignatura" ,"Convocatoria","Calificación","Nota"];
     fputcsv($archivo_notas_buenas, $encabezados);


    while (!feof($notas)) {
        $linea = fgets($notas);
        if (trim($linea) == "") {
            continue; // Saltar líneas vacías
        }

        $datos = explode(",", trim($linea)); // Dividimos las columnas por comas


        //CASO CUANDO HAY MÁS DE UNA COLUMNA PORQUE EL NOMBRE DE LA ASIGNATURA TIENE UNA COMA
        $diccionario = [
            "CTI: MAGIAS HUMANOS" => "INCLUSI",
            "ELECTIVO AREA: Gu" => "nivel superior"
        ];

        if (count($datos) > 15) {
            foreach ($diccionario as $clave => $valor) {
                if (strpos($datos[12], $clave) !== false && strpos($datos[13], $valor) !== false) {
                    $juntar = trim($datos[12] . " " . $datos[13]); 
                    $datos[12] = $juntar; 

                    $datos[13] = trim($datos[14]);
                    $datos[14] = trim($datos[15]);
                    $datos[15] = trim($datos[16]); 
                    array_splice($datos, 16, 1); 
                }
            }
        
        }


        if (empty($datos[14]) && filter_var($datos[15], FILTER_VALIDATE_FLOAT) !== false) {
            $nota = (float)$datos[15];
        
            if ($nota >= 6.6 && $nota <= 7.0) {
                $datos[14] = "SO"; 
            } elseif ($nota >= 6.0 && $nota <= 6.5) {
                $datos[14] = "MB"; 
            } elseif ($nota >= 5.0 && $nota <= 5.9) {
                $datos[14] = "B"; 
            } elseif ($nota >= 4.0 && $nota <= 4.9) {
                $datos[14] = "SU"; 
            } elseif ($nota >= 3.0 && $nota <= 3.9) {
                $datos[14] = "I"; 
            } elseif ($nota >= 2.0 && $nota <= 2.9) {
                $datos[14] = "M"; 
            } elseif ($nota >= 1.0 && $nota <= 1.9) {
                $datos[14] = "MM"; 
            } elseif (empty($nota)) {
                $datos[14] = ""; 
            }
        }

        //no funciona, quizas colocarlo como string?
        $enteros = [1,2,3,4,5,6,7];
        if (in_array($datos[15], $enteros)) {
        
            if ($datos[15] == 1) {
                $datos[15] = 1.0; 
            } elseif ($datos[15] == 2) {
                $datos[15] = 2.0; 
            } elseif ($datos[15] == 3) {
                $datos[15] = 3.0; 
            } elseif ($datos[15] == 4) {
                $datos[15] = 4.0; 
            } elseif ($datos[15] == 5) {
                $datos[15] = 5.0; 
            } elseif ($datos[15] == 6) {
                $datos[15] = 6.0; 
            } elseif ($datos[15] == 7) {
                $datos[15] = 7.0; 
            }
        }


        //$codigo_plan = ["WH2", "WH3", "WH1", "HP1", "HW2", "HP2", "HW1", "GH1", "PH1", "GH3", "PH2", "GH2", "DO1", "HG1"];

        $calificaciones_validas = ["SO", "MB", "B", "SU", "I", "M", "MM", "P", "NP", "EX", "A", "R"];

        $es_valido = (in_array($datos[14], $calificaciones_validas) || //Calificacion en la lista
                     (empty($datos[14])))&& // Calificación puede ser vacia
                     ((empty($datos[15]) || //notas vacias
                     filter_var($datos[15], FILTER_VALIDATE_FLOAT) !== false &&  // Notas solo puede ser float
                      (float)$datos[15] >= 1.0 && (float)$datos[15] <= 7.0)); // rango del 1 al 7



        if ($es_valido) {
            fputcsv($archivo_notas_buenas, $datos);
        } else {
            fputcsv($archivo_notas_malas, $datos); 
        }
    }

    fclose($notas); 
    fclose($archivo_notas_buenas);
    fclose($archivo_notas_malas); 
}

// Llamar a la función
validar_archivo_notas();


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

// Ejemplo de uso
$nombreArchivo = "cargadores/originales/Notas.csv"; // Nombre del archivo CSV
$indiceColumna = 13; // Índice de la columna que quieres analizar (empieza en 0)
$valoresUnicos = obtenerValoresUnicos($nombreArchivo, $indiceColumna);

print_r($valoresUnicos); // Imprimir los valores únicos

?>
