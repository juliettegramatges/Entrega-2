<?php
# filtrar base original
require 'cargador_asignatura.php'; 
procesar_asignaturas();

require 'cargador_docentes_planificados.php'; 
procesar_docentes_planificados();


# corregir bases datos filtrados

require 'cargador_asignaturas_malas.php'; 
corregir_asignaturas();

require 'cargador_docentes_malos.php'; 
corregir_asignaturas();




?>
