<?php
// Verificar si las cookies existen; si no, usar valores predeterminados
$language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'es'; // Idioma predeterminado: español
$style = isset($_COOKIE['style']) ? $_COOKIE['style'] : 'light'; // Estilo predeterminado: claro

// Comprobar si se han enviado nuevas preferencias desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['language']) && isset($_POST['style'])) {
        // Actualizar las cookies con los nuevos valores
        setcookie('language', $_POST['language'], time() + (30 * 24 * 60 * 60), '/'); // Válido por 30 días
        setcookie('style', $_POST['style'], time() + (30 * 24 * 60 * 60), '/'); // Válido por 30 días

        // Actualizar las variables locales
        $language = $_POST['language'];
        $style = $_POST['style'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $language === 'en' ? 'Products' : 'Productos'; ?></title>
    
    <!-- Cargar el estilo según las cookies -->
    <link rel="stylesheet" href="css/style-<?php echo $style; ?>.css">
</head>
<body class="style-<?php echo $style; ?>">
    <!-- Encabezado con botones -->
    <header>
        <div class="header-container">
            <h1><?php echo $language === 'en' ? 'My Store' : 'Mi Tienda'; ?></h1>
            <div class="header-links">
                <!-- Botón de inicio de sesión -->
                <a href="views/login.php" class="btn-link">
                    <img src="img/login-icon-<?php echo $style === 'dark' ? 'oscuro' : 'claro'; ?>.png" 
                         alt="<?php echo $language === 'en' ? 'Log In' : 'Iniciar Sesión'; ?>" 
                         class="icono">
                    <?php echo $language === 'en' ? 'Log In' : 'Iniciar Sesión'; ?>
                </a>

                <!-- Botón Ajustes -->
                <form method="POST" action="views/preferencias.php">
                    <input type="hidden" name="source" value="index"> <!-- Indicar el origen -->
                    <button type="submit" class="btn-link">
                        <img src="img/settings-icon-<?php echo $style === 'dark' ? 'oscuro' : 'claro'; ?>.png" 
                             alt="<?php echo $language === 'en' ? 'Settings' : 'Ajustes'; ?>" 
                             class="icono">
                        <?php echo $language === 'en' ? 'Settings' : 'Ajustes'; ?>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Contenido principal: Productos -->
    <main>
        <h2><?php echo $language === 'en' ? 'Available Products' : 'Productos Disponibles'; ?></h2>
        <div class="productos-container">
            <?php
            // Lista de productos (puedes reemplazar esto con datos dinámicos)
            $productos = [
                ["nombre" => $language === 'en' ? "Mouse" : "Ratón", "precio" => "20.00€", "imagen" => "img/producto1.jpg"],
                ["nombre" => $language === 'en' ? "Keyboard" : "Teclado", "precio" => "40.00€", "imagen" => "img/producto2.jpg"],
                ["nombre" => $language === 'en' ? "Monitor" : "Monitor", "precio" => "150.00€", "imagen" => "img/producto3.jpg"],
                ["nombre" => $language === 'en' ? "Headphones" : "Auriculares", "precio" => "30.00€", "imagen" => "img/producto4.jpg"],
            ];

            // Mostrar los productos
            foreach ($productos as $producto) {
                echo '
                <div class="producto">
                    <img src="' . $producto["imagen"] . '" alt="' . $producto["nombre"] . '">
                    <h3>' . $producto["nombre"] . '</h3>
                    <p class="precio">' . $producto["precio"] . '</p>
                </div>';
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <p>© 2024 <?php echo $language === 'en' ? 'My Store' : 'Mi Tienda'; ?>. <?php echo $language === 'en' ? 'All rights reserved.' : 'Todos los derechos reservados.'; ?></p>
        </div>
    </footer>
</body>
</html>
