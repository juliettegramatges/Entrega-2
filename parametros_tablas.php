<?php

$path_tablas = array(
    'asignaturas' => 'data/asignaturas_buenas.csv',
    'docentes' => 'data/docentes planificados_buenos.csv',
);


$tablas_iniciales = array(

        'usuario' => '
            id_usuario INT PRIMARY KEY, 
            correo VARCHAR(255) NOT NULL, 
            contraseña VARCHAR(255) NOT NULL
        ',

        'persona' => '
            id_persona INT PRIMARY KEY, 
            run INT NOT NULL, 
            nombre VARCHAR(255) NOT NULL, 
            apellido VARCHAR(255) NOT NULL, 
            correo_personal VARCHAR(255), 
            correo_institucional VARCHAR(255), 
            telefono VARCHAR(255) NOT NULL
        ',

        'estudiante' => '
            cohorte VARCHAR(255) NOT NULL, 
            dv CHAR(1) NOT NULL, 
            segundo_apellido VARCHAR(255),
            estado_bloqueo BOOLEAN NOT NULL,
            ultimo_logro VARCHAR(255) NOT NULL,
            ultima_carga INT NOT NULL,
            numero_alumo INT NOT NULL,
            FOREIGN KEY (id_persona) REFERENCES persona(id_persona)
        ',
        
        'academico' => '
            FOREIGN KEY (id_persona) REFERENCES persona(id_persona),
            contrato  VARCHAR(255), 
            grado_academico VARCHAR(255),
            jerarquia VARCHAR(255), 
            cargo VARCHAR(255),
            jornada_diurna BOOLEAN
            jornda_vespertina BOOLEAN
        '
    );
    
?>