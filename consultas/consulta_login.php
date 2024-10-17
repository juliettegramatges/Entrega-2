<?php
session_start();
include('config/conexion.php'); 

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar si el usuario existe
$query = $db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
$query->bindParam(':email', $email);
$query->execute();
$user = $query->fetch();

if ($user && $user['password'] === $password) { // Usa password_verify si la contraseña está hasheada
    // Iniciar sesión sin establecer el rol
    $_SESSION['user'] = $user['email'];

    // Redirigir al archivo de consultas PHP
    header("Location: consultas_php/consulta.php");
    exit();
} else {
    echo "Correo electrónico o contraseña incorrectos.";
}
?>
