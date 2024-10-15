<?php 
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); 
    exit(); 
}

include('templates/header.html'); 
?>

<body>
  <div class="user">
  <h1 class="title">Consultas</h1>
  <p class="description">Selecciona la consulta.</p>

  <h2 class="subtitle">Ranking de canciones por reproducciones</h2>
  <p class="prompt">Estudiantes vigentes dentro de nivel</p>


  <form class="form" action="consultas/consulta_1.php" method="post">
    <input class="form-input
    " type="submit" value="Buscar">
  </form>
  <br>
  <br>

  <form method="POST" action="consultas/logout.php">
    <button type="submit" class="form-button">Volver a Iniciar Sesión</button>
  </form>
  </div>
</body>
</html>