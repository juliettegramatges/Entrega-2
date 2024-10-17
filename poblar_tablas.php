<?php
include('config/conexion.php');
require('parametros_tablas.php');
require('utils.php');
try {
    echo "INICIO DE INSERCIÓN DE DATOS\n";

    // Abrir archivos
    $path_docentes = $path_tablas['docentes'];
    $file_docentes = fopen($path_docentes, 'r');
    $path_estudiantes = $path_tablas['estudiantes'];
    $file_estudiantes = fopen($path_estudiantes, 'r');
    $path_cursos = $path_tablas['asignaturas'];
    $file_cursos = fopen($path_cursos, 'r');

    echo "INSERTANDO DATOS DE ACADEMICO\n";

    // Insertar académicos
    while (($data = fgetcsv($file_docentes, 0, ';')) !== false) {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i] === '' ? null : $data[$i];
        }

        // Datos académicos
        $run = intval($data[0]);
        $nombre = $data[1];
        $apellido = $data[2];
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


        // Determinar qué jornada está activa
        if (!empty($jornada_diurna) && empty($jornada_vespertina)) {
            $jornada = 'diurna';
        } elseif (empty($jornada_diurna) && !empty($jornada_vespertina)) {
            $jornada = 'vespertina';
        } else {
            $jornada = null;
        }


        $insert_persona = [
            'run' => $run,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo_personal' => $correo_personal,
            'correo_institucional' => $correo_institucional,
        ];
        $columnas = ['run', 'nombre', 'apellido', 'correo_personal', 'correo_institucional'];
        insertar_en_tabla($db, 'persona', $insert_persona, $columnas);

        $id_persona = $db->lastInsertId(); // Obtener ID de persona

        $jornada = !empty($jornada_diurna) && empty($jornada_vespertina) ? 'diurna' : (empty($jornada_diurna) && !empty($jornada_vespertina) ? 'vespertina' : null);
        $insert_academico = [
            'id_persona' => $id_persona,
            'contrato' => $contrato,
            'grado_academico' => $grado_academico,
            'jerarquia' => $jerarquia,
            'cargo' => $cargo,
            'jornada' => $jornada,
        ];
        insertar_en_tabla($db, 'academico', $insert_academico, ['id_persona', 'contrato', 'grado_academico', 'jerarquia', 'cargo', 'jornada']);
    }

    fclose($file_docentes);



    echo "INSERTANDO DATOS DE ESTUDIANTE\n";

    // Insertar estudiantes
    while (($data = fgetcsv($file_estudiantes, 0, ',')) !== false) {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i] === "" ? null : $data[$i];
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

        $insert_persona = [
            'run' => $run,
            'nombre' => $nombre_completo,
            'apellido' => $data[10] . ' ' . $data[11],
            'correo_personal' => null,
            'correo_institucional' => null,
        ];
        insertar_en_tabla($db, 'persona', $insert_persona, $columnas);

        $id_persona = $db->lastInsertId();

        // Cambiar 'numero_alumo' a 'numero_alumno'
        $insert_estudiante = [
            'id_persona' => $id_persona,
            'cohorte' => $data[2],
            'dv' => $data[7],
            'segundo_apellido' => $data[11],
            'estado_bloqueo' => $data[4],
            'fecha_logro' => $data[13],
            'ultimo_logro' => $data[12],
            'ultima_carga' => $data[14],
            'numero_alumno' => intval($data[3]), // Corregido aquí
        ];
        insertar_en_tabla($db, 'estudiante', $insert_estudiante, ['id_persona', 'cohorte', 'dv', 'segundo_apellido', 'estado_bloqueo', 'fecha_logro', 'ultimo_logro', 'ultima_carga', 'numero_alumno']);
    }

    fclose($file_planes);



        // Abrir archivos
    $path_docentes = $path_tablas['docentes'];
    $file_docentes = fopen($path_docentes, 'r');
    $path_estudiantes = $path_tablas['estudiantes'];
    $file_estudiantes = fopen($path_estudiantes, 'r');
    $path_planes = $path_tablas['planes_estudios'];
    $file_planes = fopen($path_planes, 'r');



    echo "INSERTANDO DATOS DE A PLAN_ESTUDIOS\n";

    while (($data = fgetcsv($file_planes, 0, ',')) !== false) {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i] === '' ? null : $data[$i];
        }

        $codigo = $data[0];
        $nombre = $data[3];
        $fecha_inicio = $data[8];
        $jornada = $data[4];
        $modalidad = $data[7];

        $insert_plan = [
            'codigo' => $codigo,
            'nombre' => $nombre,
            'fecha_inicio' => $fecha_inicio,
            'jornada' => $jornada,
            'modalidad' => $modalidad,
        ];
        $columnas = ['codigo', 'nombre', 'fecha_inicio', 'jornada', 'modalidad'];
        insertar_en_tabla($db, 'plan_estudios', $insert_plan, $columnas);
        }

        fclose($file_docentes);


    echo "INSERTANDO DATOS DE CURSOS\n";

    // Insertar estudiantes
    while (($data = fgetcsv($file_cursos, 0, ';')) !== false) {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i] === "" ? null : $data[$i];
        }
            $sigla = $data[1];
            $nombre = $data[2]; 
            $nivel = $data[3];
            $id_plan_estudio = $data[0];
    
        $insert_curso = [
            'sigla' => $sigla,
            'nombre' => $nombre,
            'nivel' => $nivel,
        ];
        $columnas = ['sigla', 'nombre', 'nivel'];
        insertar_en_tabla($db, 'curso', $insert_curso, $columnas);
        }

        fclose($file_cursos);

    echo "INSERCIÓN COMPLETADA\n";

} catch (Exception $e) {
    echo "Error al insertar: " . $e->getMessage();
}


?>