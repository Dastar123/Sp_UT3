<?php
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Eliminar las cookies relacionadas con la sesión y el carrito
if (isset($_COOKIE['carritoCookie'])) {
    setcookie('carritoCookie', '', time() - 3600, '/');
}
if (isset($_COOKIE['language'])) {
    setcookie('language', '', time() - 3600, '/');
}
if (isset($_COOKIE['style'])) {
    setcookie('style', '', time() - 3600, '/');
}
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/');
}

// Redirigir al inicio después de cerrar sesión (una vez)
header('Location: ../index.php');
exit;
?>
