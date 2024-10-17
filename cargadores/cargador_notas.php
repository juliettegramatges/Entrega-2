<?php
ini_set('memory_limit', '512M');

function validar_archivo_notas() {
    $notas = fopen("cargadores/originales/Notas.csv", "r");
    stream_filter_append($notas, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_notas_buenas = fopen("data/notas_buenas.csv", "w");
    $archivo_notas_malas = fopen("data_malo/notas_malas.csv", "w");

    // Conjunto para almacenar filas únicas
    $filas_unicas = [];

    while (($linea = fgets($notas)) !== false) {
        $linea = trim($linea);
        if ($linea === "") {
            continue; // Saltar líneas vacías
        }

        $datos = explode(",", $linea); // Dividir las columnas por comas

        // Comprobación de la validez de los datos
        if (es_valido($datos)) {
            $fila_clave = implode(",", $datos);

            // Comprobar si la fila ya fue registrada
            if (!isset($filas_unicas[$fila_clave])) {
                fputcsv($archivo_notas_buenas, $datos);
                $filas_unicas[$fila_clave] = true; // Almacenar fila como única
            }
        } else {
            fputcsv($archivo_notas_malas, $datos); 
        }
    }

    fclose($notas); 
    fclose($archivo_notas_buenas);
    fclose($archivo_notas_malas); 
}

function es_valido($datos) {
    $calificaciones_validas = ["SO", "MB", "B", "SU", "I", "M", "MM", "P", "NP", "EX", "A", "R"];
    return (in_array($datos[14], $calificaciones_validas) || empty($datos[14])) &&
           (empty($datos[15]) || 
           (filter_var($datos[15], FILTER_VALIDATE_FLOAT) !== false &&
           (float)$datos[15] >= 1.0 && (float)$datos[15] <= 7.0));
}

?>
