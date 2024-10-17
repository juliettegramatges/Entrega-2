<?php
function procesar_asignaturas() {
    // Abrir archivos
    $asignaturas = fopen("cargadores/originales/Asignaturas.csv", "r");
    stream_filter_append($asignaturas, 'convert.iconv.ISO-8859-1/UTF-8');
    $archivo_buenos = fopen("data/asignaturas_buenas.csv", "w");
    $archivo_malos = fopen("data_malo/asignaturas_malas.csv", "w");

    // Escribir encabezados en el archivo de buenos
    $encabezados = ["Plan", "Asignatura id", "Asignatura", "Nivel"];
    fputcsv($archivo_buenos, $encabezados, ';');

    // Array para almacenar los identificadores únicos
    $asignaturas_unicas = [];

    while (($datos = fgetcsv($asignaturas)) !== FALSE) {
        if (count($datos) < 4) {
            // Si hay menos de 4 columnas, guardar en archivos malos
            fputcsv($archivo_malos, $datos, ';');
            continue;
        }

        // Limpiar la columna de datos[2]: eliminar comillas y espacios en blanco al inicio y al final
        $datos[2] = trim(str_replace('"', '', $datos[2]));

        // Validar los datos
        $es_valido = is_string($datos[0]) && preg_match('/^[A-Z0-9]+$/', $datos[0]) &&  // Plan solo letras mayúsculas
                     is_string($datos[1]) && preg_match('/^[A-ZÁÉÍÓÚÑ0-9]+$/i', $datos[1]) &&  // Asignatura solo letras
                     is_string($datos[2]) && preg_match('/^[A-Z0-9 :()-ÁÉÍÓÚÑ]+$/', $datos[2]) && // Asignatura ID con letras, números, espacios y dos puntos
                     ctype_digit($datos[3]) &&  // Nivel solo números enteros
                     !empty(trim($datos[1])) &&  // Asegurar que "Asignatura" no esté vacía
                     !empty(trim($datos[2])); // Asegurar que "Asignatura id" no esté vacía

        // Verificar si hay dos espacios consecutivos en datos[2]
        if (preg_match('/ {2,}/', $datos[2])) {
            // Si hay dos espacios consecutivos, agregar a archivos malos
            fputcsv($archivo_malos, $datos, ';');
        } else if ($es_valido) {
            // Verificar si la asignatura ya existe
            $asignatura_id = $datos[2]; // Suponiendo que "Asignatura id" está en datos[2]
            if (!in_array($asignatura_id, $asignaturas_unicas)) {
                // Agregar a la lista de asignaturas únicas
                $asignaturas_unicas[] = $asignatura_id;

                // Escribir la fila en asignaturas buenas si es válida
                fputcsv($archivo_buenos, $datos, ';');
            }
        } else {
            // Si no es válido, agregar a archivos malos
            fputcsv($archivo_malos, $datos, ';');
        }
    }

    // Cerrar archivos
    fclose($asignaturas);
    fclose($archivo_buenos);
    fclose($archivo_malos);
}
?>
