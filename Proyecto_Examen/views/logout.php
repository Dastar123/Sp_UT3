<?php
session_start();

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir al formulario de inicio de sesión
header('Location: ../index.php');
exit;
?>
