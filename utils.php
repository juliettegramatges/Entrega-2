<?php
function insertar_en_tabla($database, $tabla, $fila, $columnas){
    try {
        $valores = array_values($fila);
        $database->beginTransaction();
        
        // Crear una cadena de marcadores de posición
        $placeholders = implode(',', array_fill(0, count($valores), '?'));
        
        // Incluir nombres de columnas en la consulta
        $sql = "INSERT INTO $tabla (" . implode(", ", $columnas) . ") VALUES ($placeholders);";
        $stmt = $database->prepare($sql);
        $stmt->execute($valores);
        $database->commit();

    } catch (Exception $e) {
        $database->rollBack();
        echo "Error al insertar en la tabla $tabla: " . $e->getMessage();
    } 
}
?>
