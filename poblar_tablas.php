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

        while (($data = fgetcsv($file_docentes, 0, ';')) !== false) {
            // Verificar restricciones antes de insertar
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] === '') {
                    $data[$i] = null; // Convertir campos vacíos en NULL
                }
            }


            $run = intval($data[0]);
            $nombre = $data[1];
            $apellido = $data[2];
            $telefono = intval($data[3]);
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
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo_personal' => $correo_personal,
                'correo_institucional' => $correo_institucional,
                'telefono' => $telefono
            ];

            $columnas = ['run', 'nombre', 'apellido', 'correo_personal', 'correo_institucional', 'telefono'];



            // Realizar la inserción en persona
            insertar_en_tabla($db, 'persona', $insert_persona, $columnas);

            // Insertar en la tabla academico


            // Determinar qué jornada está activa
            if (!empty($jornada_diurna) && empty($jornada_vespertina)) {
                $jornada = 'diurna';
            } elseif (empty($jornada_diurna) && !empty($jornada_vespertina)) {
                $jornada = 'vespertina';
            } else {
                $jornada = null; // O algún valor predeterminado si ambos están vacíos
            }
            $insert_academico = [
                'contrato' => $contrato,
                'grado_academico' => $grado_academico,
                'jerarquia' => $jerarquia,
                'cargo' => $cargo,
                'jornada' => $jornada,
            ];




            $columnas = ['contrato', 'grado_academico', 'jerarquia', 'cargo', 'jornada'];


            // Realizar la inserción en academico
            insertar_en_tabla($db, 'academico', $insert_academico, $columnas);
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
        while (($data = fgetcsv($file_estudiantes, 0, ',')) !== false) {
            // Verificar restricciones antes de insertar
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] === "") {
                    $data[$i] = null; // Convertir campos vacíos en NULL
                }
            }

            $codigo_plan = $data[0]; 
            $carrera = $data[1];
            $cohorte = $data[2]; 
            $numero_alumno = intval($data[3]);
            $estado_bloqueo = $data[4];
            $causal_bloqueo = $data[5];
            $run = intval($data[6]);
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
                'correo_personal' => null, // Establecer como null si está vacío
                'correo_institucional' => null, // Hacer lo mismo con el correo institucional
                'telefono' => $telefono, // Debes obtener esto correctamente
            ];

            $columnas = ['run', 'nombre', 'apellido', 'correo_personal', 'correo_institucional', 'telefono'];

            // Realizar la inserción en persona
            insertar_en_tabla($db, 'persona', $insert_persona, $columnas);

            // Obtener el id_persona de la última inserción

            // Insertar en la tabla estudiante
            $insert_estudiante = [
                'cohorte' => $cohorte,
                'dv' => $dv,
                'segundo_apellido' => $segundo_apellido,
                'estado_bloqueo' => $estado_bloqueo,
                'fecha_logro' => $fecha_ultimo_logro,
                'ultimo_logro' => $ultimo_logro,
                'ultima_carga' => $ultima_carga,
                'numero_alumo' => $numero_alumno,
            ];

            $columnas = ['cohorte', 'dv', 'segundo_apellido', 'estado_bloqueo', 'fecha_logro', 'ultimo_logro', 'ultima_carga', 'numero_alumo'];

            // Realizar la inserción en estudiante
            insertar_en_tabla($db, 'estudiante', $insert_estudiante, $columnas);
        }
        fclose($file_estudiantes);
    } else {
        echo "Error al abrir el archivo $path_estudiantes\n";
    }

    echo "INICIO DE INSERCIÓN DE DATOS EN LA TABLA ESTUDIANTE\n";

    // Abrir el archivo de estudiantes
    $path_estudiantes = $path_tablas['estudiantes'];
    $file_estudiantes = fopen($path_estudiantes, 'r');

    if ($file_estudiantes) {
        while (($data = fgetcsv($file_estudiantes, 0, ',')) !== false) {
            // Verificar restricciones antes de insertar
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] === "") {
                    $data[$i] = null; // Convertir campos vacíos en NULL
                }
            }

            $codigo_plan = $data[0]; 
            $carrera = $data[1];
            $cohorte = $data[2]; 
            $numero_alumno = intval($data[3]);
            $estado_bloqueo = $data[4];
            $causal_bloqueo = $data[5];
            $run = intval($data[6]);
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
                'correo_personal' => null, // Establecer como null si está vacío
                'correo_institucional' => null, // Hacer lo mismo con el correo institucional
                'telefono' => $telefono, // Debes obtener esto correctamente
            ];

            $columnas = ['run', 'nombre', 'apellido', 'correo_personal', 'correo_institucional', 'telefono'];

            // Realizar la inserción en persona
            insertar_en_tabla($db, 'persona', $insert_persona, $columnas);

            // Obtener el id_persona de la última inserción

            // Insertar en la tabla estudiante
            $insert_estudiante = [
                'cohorte' => $cohorte,
                'dv' => $dv,
                'segundo_apellido' => $segundo_apellido,
                'estado_bloqueo' => $estado_bloqueo,
                'fecha_logro' => $fecha_ultimo_logro,
                'ultimo_logro' => $ultimo_logro,
                'ultima_carga' => $ultima_carga,
                'numero_alumo' => $numero_alumno,
            ];

            $columnas = ['cohorte', 'dv', 'segundo_apellido', 'estado_bloqueo', 'fecha_logro', 'ultimo_logro', 'ultima_carga', 'numero_alumo'];

            // Realizar la inserción en estudiante
            insertar_en_tabla($db, 'estudiante', $insert_estudiante, $columnas);
        }
        fclose($file_estudiantes);
    } else {
        echo "Error al abrir el archivo $path_estudiantes\n";
    }



} catch (Exception $e) {
    echo "Error al cargar datos: " . $e->getMessage();
}
?>
