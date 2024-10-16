<?php
# 1. filtrar base original
require 'cargadores/cargador_asignatura.php'; 
procesar_asignaturas();

require 'cargadores/cargador_docentes_planificados.php'; 
procesar_docentes_planificados();

require 'cargadores/cargador_estudiante.php'; 
procesar_estudiantes();

require 'cargadores/cargador_planeacion.php'; 
procesar_planeacion();

require 'cargadores/cargador_prerequisito.php'; 
procesar_prerequisitos();


# 2. corregir bases datos filtrados

require 'correcciones/cargador_asignaturas_malas.php'; 
corregir_asignaturas();

require 'correcciones/cargador_docentes_malos.php'; 
corregir_docentes();

require 'correcciones/corrector_planeacion.php'; 
corregirPlaneacion();

require 'correcciones/corrector_prerequisito.php'; 
corregirPrerequisitos();



require_once('config/conexion.php');
require_once('crear_tablas.php');
require_once('poblar_tablas.php');
echo "TODO LISTO";


?>
