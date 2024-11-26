<?php
// Iniciar sesión
session_start();

// Función para guardar el valor de 'source'
function guardarPos($source) {
    return $source; // Guardamos el valor de source para usarlo en la redirección
}

// Función para redirigir según la página de origen
function redirigir($source) {
    // Definir las rutas según el valor de 'source'
    $rutas = [
        'home' => 'home.php',
        'registro' => 'resgistro.php',
        'login' => 'login.php',
        'productos' => 'productos.php',
        'index' => '../index.php',
    ];

    // Verificar si la ruta existe en el array
    if (array_key_exists($source, $rutas)) {
        return $rutas[$source]; // Devuelve la ruta correspondiente
    } else {
        return 'index.php'; // Página predeterminada
    }
}

// Verificar si se han enviado datos vía POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurarse de que los datos se están enviando correctamente
    if (isset($_POST['source'])) {
        // Guardar el valor de 'source' usando la función guardarPos
        $sourceGuardado = guardarPos($_POST['source']);
        // Redirigir según la página guardada
        $redirigirUrl = redirigir($sourceGuardado);

        // Verificar si se enviaron preferencias (idioma y estilo)
        if (isset($_POST['language']) && isset($_POST['style'])) {
            // Eliminar las cookies existentes, si es que hay
            if (isset($_COOKIE['language'])) {
                setcookie('language', '', time() - 3600, '/'); // Eliminar cookie de idioma
            }
            if (isset($_COOKIE['style'])) {
                setcookie('style', '', time() - 3600, '/'); // Eliminar cookie de estilo
            }

            // Crear nuevas cookies con los valores seleccionados
            setcookie('language', $_POST['language'], time() + (30 * 24 * 60 * 60), '/'); // Cookie por 30 días
            setcookie('style', $_POST['style'], time() + (30 * 24 * 60 * 60), '/'); // Cookie por 30 días
        }
    } else {
        // Si no se envió 'source', muestra un mensaje de error
        echo "Error: No se enviaron los datos correctamente.";
    }
}

// **Importante**: Ahora, para leer las cookies en la misma ejecución después de haber sido establecidas o eliminadas, debemos verificar si existen las cookies antes de mostrar los valores predeterminados
// Comprobar si las cookies existen y obtener los valores de las preferencias
$language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'es'; // Valor predeterminado: español
$style = isset($_COOKIE['style']) ? $_COOKIE['style'] : 'light'; // Valor predeterminado: estilo claro (light)

// Verificar si el parámetro 'source' existe en la URL
$source = isset($_GET['source']) ? $_GET['source'] : 'index'; // Si no se pasa 'source', se usa 'index' como valor predeterminado
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferencias de Usuario</title>
    <link rel="stylesheet" href="../css/style-<?php echo $style; ?>.css"> <!-- Cargar el estilo según las preferencias -->
</head>
<body>
    <h2>Preferencias de Usuario</h2>
    <div class="form-container">
        <form method="POST" action="<?php echo isset($redirigirUrl) ? $redirigirUrl : ''; ?>">
            <!-- Selección de idioma -->
            <label for="language">Selecciona el idioma:</label>
            <select name="language" id="language">
                <option value="es" <?php echo ($language == 'es') ? 'selected' : ''; ?>>Español</option>
                <option value="en" <?php echo ($language == 'en') ? 'selected' : ''; ?>>Inglés</option>
            </select>
            <br><br>

            <!-- Selección de estilo -->
            <label for="style">Selecciona el estilo:</label>
            <select name="style" id="style">
                <option value="light" <?php echo ($style == 'light') ? 'selected' : ''; ?>>Claro</option>
                <option value="dark" <?php echo ($style == 'dark') ? 'selected' : ''; ?>>Oscuro</option>
            </select>
            <br><br>

            <!-- Campo oculto para saber de qué página venimos -->
            <input type="hidden" name="source" value="<?php echo $source; ?>">

            <!-- Botón de guardar preferencias -->
            <button type="submit">Guardar preferencias</button>
        </form>
    </div>
</body>
</html>
