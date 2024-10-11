<?php
include('config/conexion.php');
require('parametros_tablas.php');
require('utils.php');

try {
    echo "INICIO DE INSERCIÓN DE DATOS\n";

    // Abrir el archivo de docentes
    $path_docentes = $path_tablas['docentes'];
    $file_docentes = fopen($path_docentes, 'r');

    if ($file_docentes) {
        echo "INICIO DE INSERCIÓN DE DATOS EN LA TABLA ACADEMICO\n";
        $header = fgetcsv($file_docentes); // Saltar la primera línea (cabecera)

        while (($data = fgetcsv($file_docentes, 0, ',')) !== false) {
            // Verificar restricciones antes de insertar
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] == '') {
                    $data[$i] = null; // Convertir campos vacíos en NULL
                }
            }

            $run = $data[0];
            $nombre = $data[1];
            $apellido = $data[2];
            $telefono = $data[3];
            $correo_personal = $data[4];
            $correo_institucional = $data[5];
            $dedicacion = $data[6];
            $contrato = $data[7];
            $jornada_diurna = $data[8];
            $jornada_vespertina = $data[9];
            $sede = $data[10];
            $carrera = $data[11];
            $grado_academico = $data[12];
            $jerarquia = $data[13];
            $cargo = $data[14];
            $estamento = $data[15];

            // Insertar en la tabla persona
            $insert_persona = [
                'run' => $run,
                'dv' => !empty($dv) ? $dv : null, // Establecer como null si está vacío
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo_personal' => $correo_personal,
                'correo_institucional' => $correo_institucional,
                'telefono' => $telefono,
            ];

            // Realizar la inserción en persona
            insertar_en_tabla($db, 'persona', $insert_persona);

            // Obtener el id_persona de la última inserción
            $id_persona = $db->lastInsertId();

            // Insertar en la tabla academico
            $insert_academico = [
                'id_persona' => $id_persona,
                'contrato' => $contrato,
                'grado_academico' => $grado_academico,
                'jerarquia' => $jerarquia,
                'cargo' => $cargo,
                'jornada' => $jornada_diurna, // o $jornada_vespertina según sea necesario
            ];

            // Realizar la inserción en academico
            insertar_en_tabla($db, 'academico', $insert_academico);
        }
        fclose($file_docentes);
    } else {
        echo "Error al abrir el archivo $path_docentes\n";
    }

    echo "INICIO DE INSERCIÓN DE DATOS EN LA TABLA ESTUDIANTE\n";

    // Abrir el archivo de estudiantes
    $path_estudiantes = $path_tablas['estudiantes'];
    $file_estudiantes = fopen($path_estudiantes, 'r');

    if ($file_estudiantes) {
        $header = fgetcsv($file_estudiantes); // Saltar la primera línea (cabecera)

        while (($data = fgetcsv($file_estudiantes, 0, ',')) !== false) {
            // Verificar restricciones antes de insertar
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] == '') {
                    $data[$i] = null; // Convertir campos vacíos en NULL
                }
            }

            $codigo_plan = $data[0]; 
            $carrera = $data[1];
            $cohorte = $data[2]; 
            $numero_alumno = $data[3];
            $estado_bloqueo = $data[4];
            $causal_bloqueo = $data[5];
            $run = $data[6];
            $dv = $data[7];
            $primer_nombre = $data[8];
            $segundo_nombre = $data[9];
            $primer_apellido = $data[10];
            $segundo_apellido = $data[11];
            $ultimo_logro = $data[12];
            $fecha_ultimo_logro = $data[13];
            $ultima_carga = $data[14];

            // Concatenar nombres
            $nombre_completo = trim($primer_nombre . ' ' . $segundo_nombre);

            // Insertar en la tabla persona
            $insert_persona = [
                'run' => $run,
                'nombre' => $nombre_completo,
                'apellido' => $primer_apellido . ' ' . $segundo_apellido,
                'correo_personal' => !empty($correo_personal) ? $correo_personal : null, // Establecer como null si está vacío
                'correo_institucional' => !empty($correo_institucional) ? $correo_institucional : null, // Hacer lo mismo con el correo institucional
                'telefono' => $telefono, // Debes obtener esto correctamente
            ];

            // Realizar la inserción en persona
            insertar_en_tabla($db, 'persona', $insert_persona);

            // Obtener el id_persona de la última inserción
            $id_persona = $db->lastInsertId();

            // Insertar en la tabla estudiante
            $insert_estudiante = [
                'id_persona' => $id_persona,
                'cohorte' => $cohorte,
                'dv' => $dv,
                'segundo_apellido' => $segundo_apellido,
                'estado_bloqueo' => $estado_bloqueo,
                'ultimo_logro' => $ultimo_logro,
                'ultima_carga' => $ultima_carga,
                'numero_alumno' => $numero_alumno,
            ];

            // Realizar la inserción en estudiante
            insertar_en_tabla($db, 'estudiante', $insert_estudiante);
        }
        fclose($file_estudiantes);
    } else {
        echo "Error al abrir el archivo $path_estudiantes\n";
    }

} catch (Exception $e) {
    echo "Error al cargar datos: " . $e->getMessage();
}
?>
