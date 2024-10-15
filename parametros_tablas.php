<?php

$path_tablas = array(
    'asignaturas' => 'data/asignaturas_buenas.csv',
    'docentes' => 'data/docentes planificados_buenos.csv',
    'estudiantes' => 'data/estudiantes_buenas.csv',
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
            correo_institucional VARCHAR(255), 
            telefono INT NOT NULL
        ',

        'estudiante' => '
            id_estudiante SERIAL PRIMARY KEY,
            FOREIGN KEY (id_estudiante) REFERENCES persona(id_persona),
            cohorte VARCHAR(7) NOT NULL, 
            dv CHAR(1) NOT NULL, 
            segundo_apellido VARCHAR(255),
            estado_bloqueo VARCHAR(1),
            fecha_logro VARCHAR(7),
            ultimo_logro VARCHAR(255) NOT NULL,
            ultima_carga VARCHAR(7),
            numero_alumo INT NOT NULL
        ',
        
        'academico' => '
            id_academico SERIAL PRIMARY KEY,
            FOREIGN KEY (id_academico) REFERENCES persona(id_persona),
            contrato  VARCHAR(255), 
            grado_academico VARCHAR(255),
            jerarquia VARCHAR(255), 
            cargo VARCHAR(255),
            jornada VARCHAR(255)
        '
    );
    
?>