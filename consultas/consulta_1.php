<?php

    include('config/conexion.php');

    // Obtener estudiantes vigentes que tomaron cursos en 2024-2
$query = "SELECT cohorte, fecha_ultimo_logro FROM estudiantes WHERE fecha_ultimo_logro IS NOT NULL";
$result = pg_query($conn, $query);

$dentro_nivel = 0;
$fuera_nivel = 0;

$niveles_esperados = [
    '2020-1' => '2024-2',
    '2021-1' => '2024-1',
    '2022-1' => '2024-1',
    '2023-1' => '2024-1',
    '2024-1' => '2024-2',
];

// Procesar resultados
while ($row = pg_fetch_assoc($result)) {
    $cohorte = $row['cohorte'];
    $fecha_ultimo_logro = new DateTime($row['fecha_ultimo_logro']);
    
    // Determinar el semestre del último logro
    $semestre_ultimo_logro = ($fecha_ultimo_logro->format('n') <= 6) 
        ? $fecha_ultimo_logro->format('Y') . '-1' 
        : $fecha_ultimo_logro->format('Y') . '-2';
    
    // Comparar con el nivel esperado
    if (array_key_exists($cohorte, $niveles_esperados) && $semestre_ultimo_logro == $niveles_esperados[$cohorte]) {
        $dentro_nivel++;
    } else {
        $fuera_nivel++;
    }
}

// Generar el reporte
$reporte = [
    'Total Estudiantes Vigentes' => $dentro_nivel + $fuera_nivel,
    'Dentro de Nivel' => $dentro_nivel,
    'Fuera de Nivel' => $fuera_nivel
];

// Imprimir el reporte
echo "<pre>";
print_r($reporte);
echo "</pre>";

?>

<?php include('templates/footer_admin.html'); ?>