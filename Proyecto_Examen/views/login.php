<?php
// Iniciar sesión
session_start();

// Definir el mensaje de bienvenida si es necesario
$welcomeMessage = '';

// Cargar preferencias de idioma y estilo desde las cookies
$language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'es'; // Valor predeterminado: español
$style = isset($_COOKIE['style']) ? $_COOKIE['style'] : 'light'; // Valor predeterminado: estilo claro (light)

// Ruta al archivo de usuarios
$usersFile = '../views/users.txt'; 

// Asegúrate de que el archivo exista
if (!file_exists($usersFile)) {
    die("El archivo de usuarios no existe.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los campos 'username' y 'password' están definidos en el formulario
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $isAdmin = isset($_POST['isAdmin']) ? true : false; // Verificar si el usuario marca el checkbox como admin

        // Leer el archivo de usuarios
        $users = file($usersFile, FILE_IGNORE_NEW_LINES); // Leer las líneas del archivo
        $userFound = false;

        // Recorrer los usuarios en el archivo
        foreach ($users as $user) {
            list($storedUsername, $storedPassword, $storedRole) = explode('|', $user);

            // Verificar si el nombre de usuario coincide
            if ($storedUsername == $username) {
                // Verificar la contraseña usando password_verify
                if (password_verify($password, $storedPassword)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $storedRole;

                    // Verificar el rol basado en el checkbox
                    if (($isAdmin && $storedRole == 'admin') || (!$isAdmin && $storedRole == 'user')) {
                        // Si el rol y el checkbox coinciden, redirigir
                        $welcomeMessage = $storedRole == 'admin' ? '¡Bienvenido, Administrador!' : '¡Bienvenido, Usuario!';
                        header("Location: carrito.php"); // Página para el usuario
                        exit(); // Salir después de la redirección
                    } else {
                        // Si el rol no coincide con el checkbox
                        $welcomeMessage = $isAdmin ? 'El usuario no es administrador.' : 'El usuario no es un usuario normal.';
                        break; // Romper el ciclo porque el rol no es el esperado
                    }
                } else {
                    $welcomeMessage = 'Contraseña incorrecta.';
                    break; // Romper el ciclo porque la contraseña no es válida
                }
            }
        }

        // Si el usuario no se encuentra o la contraseña no es válida
        if (!$userFound) {
            $welcomeMessage = 'Credenciales incorrectas.';
        }
    } else {
        // Si no se ha enviado el nombre de usuario o la contraseña
        $welcomeMessage = 'Por favor, ingresa tu nombre de usuario y contraseña.';
    }
}

// Si se ha enviado el formulario de preferencias, actualizar las cookies de idioma y estilo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language']) && isset($_POST['style'])) {
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

    // Actualizar las variables locales para reflejar las preferencias
    $language = $_POST['language'];
    $style = $_POST['style'];
}

?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio de Sesión</title>
    <!-- Carga dinámica del estilo según las preferencias guardadas en las cookies -->
    <link rel="stylesheet" href="../css/style-<?php echo $style; ?>.css">
</head>
<body>
    <h2><?php echo ($language == 'es') ? 'Formulario de Inicio de Sesión' : 'Login Form'; ?></h2>

    <header>
        <div class="header-links">
            <!-- Botón Ajustes --> 
           <form method="POST" action="preferencias.php">
                    <input type="hidden" name="source" value="login"> <!-- Campo oculto con el origen -->
                    <button type="submit" class="btn-link">
                        <img src="../img/settings-icon-<?php echo $style == 'dark' ? 'oscuro' : 'claro'; ?>.png" alt="<?php echo $language == 'en' ? 'Settings' : 'Ajustes'; ?>" class="icono">
                        <?php echo $language == 'en' ? 'Settings' : 'Ajustes'; ?>
                    </button>
                </form>
        </div>
    </header>

    <?php if (!empty($welcomeMessage)) { echo "<p>$welcomeMessage</p>"; } ?>

    <form method="POST" action="">
        <label for="username"><?php echo ($language == 'es') ? 'Nombre de usuario:' : 'Username:'; ?></label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="password"><?php echo ($language == 'es') ? 'Contraseña:' : 'Password:'; ?></label>
        <input type="password" id="password" name="password" required>
        <br>

        <label for="isAdmin"><?php echo ($language == 'es') ? '¿Eres administrador?' : 'Are you an administrator?'; ?></label>
        <input type="checkbox" id="isAdmin" name="isAdmin">
        <br>

        <button type="submit"><?php echo ($language == 'es') ? 'Iniciar sesión' : 'Login'; ?></button>
    </form>

    <p><?php echo ($language == 'es') ? 'Si no tienes cuenta, ' : 'If you don\'t have an account, '; ?><a href="resgistro.php"><?php echo ($language == 'es') ? 'regístrate aquí' : 'register here'; ?></a>.</p>
</body>
</html>
