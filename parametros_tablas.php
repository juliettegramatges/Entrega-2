<?php

$path_tablas = array(
    'asignaturas' => 'data/asignaturas_buenas.csv',
    'docentes' => 'data/docentes planificados_buenos.csv',
    'estudiantes' => 'data/estudiantes_buenas.csv',
    'planes_estudios' => 'data/planes_buenas.csv',
    'notas' => 'data/notas_buenas.csv',
);

$tablas_iniciales = array(

    'usuario' => '
        id_usuario SERIAL PRIMARY KEY, 
        correo VARCHAR(255) NOT NULL, 
        contraseña VARCHAR(255) NOT NULL
    ',

    'persona' => '
        id_persona SERIAL PRIMARY KEY, 
        run INT NOT NULL, 
        nombre VARCHAR(255) NOT NULL, 
        apellido VARCHAR(255) NOT NULL, 
        correo_personal VARCHAR(255), 
        correo_institucional VARCHAR(255)
    ',

    'estudiante' => '
        id_estudiante SERIAL PRIMARY KEY,
        id_persona INT NOT NULL,  -- Se agrega el id_persona para referencia
        FOREIGN KEY (id_persona) REFERENCES persona(id_persona) ON DELETE CASCADE,
        cohorte VARCHAR(7) NOT NULL, 
        dv CHAR(1) NOT NULL, 
        segundo_apellido VARCHAR(255),
        estado_bloqueo VARCHAR(1),
        fecha_logro VARCHAR(7),
        ultimo_logro VARCHAR(255) NOT NULL,
        ultima_carga VARCHAR(7),
        numero_alumno INT NOT NULL
    ',

    'plan_estudios' => '
        id_plan SERIAL PRIMARY KEY,
        codigo VARCHAR(255) NOT NULL,
        nombre VARCHAR(255) NOT NULL,
        fecha_inicio DATE VARCHAR(255) NOT NULL,
        jornada VARCHAR(255) NOT NULL,
        modalidad VARCHAR(255)
    ',

    'curso' => '
        id_curso SERIAL PRIMARY KEY,
        sigla VARCHAR(4) NOT NULL, 
        nombre VARCHAR(255) NOT NULL,
        nivel VARCHAR(255),
    ',

    'academico' => '
        id_docente SERIAL PRIMARY KEY,
        id_persona INT NOT NULL,  -- Se agrega el id_persona para referencia
        FOREIGN KEY (id_persona) REFERENCES persona(id_persona) ON DELETE CASCADE,
        contrato  VARCHAR(255),
        grado_academico VARCHAR(255),
        jerarquia VARCHAR(255), 
        cargo VARCHAR(255),
        jornada VARCHAR(255)
    ',

    'historial_academico' => '
        id_estudiante INT NOT NULL,
        id_curso INT NOT NULL,
        nota FLOAT,
        calificacion VARCHAR(255),
        descripcion VARCHAR(255),
        PRIMARY KEY (id_estudiante, id_curso),
        FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante) ON DELETE CASCADE,
        FOREIGN KEY (id_curso) REFERENCES curso(id_curso) ON DELETE CASCADE
    ',
);

?>
