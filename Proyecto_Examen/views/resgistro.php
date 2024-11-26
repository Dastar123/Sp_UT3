<?php
// Iniciar sesión
session_start();

// Mensaje de registro
$registerMessage = '';

// Cargar preferencias de idioma y estilo desde las cookies
$language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'es'; // Valor predeterminado: español
$style = isset($_COOKIE['style']) ? $_COOKIE['style'] : 'light'; // Valor predeterminado: estilo claro (light)

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los campos están definidos
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $isAdmin = isset($_POST['isAdmin']) ? true : false; // Verificar si el usuario es admin

    // Validar que las contraseñas coinciden
    if ($password !== $confirm_password) {
        $registerMessage = ($language == 'es') 
            ? "Las contraseñas no coinciden." 
            : "Passwords do not match.";
    } else {
        // Verificar si el nombre de usuario ya existe en el archivo
        $usersFile = '../views/users.txt'; // Archivo para almacenar los usuarios
        $users = file($usersFile, FILE_IGNORE_NEW_LINES); // Leer el archivo
        $userExists = false;

        foreach ($users as $user) {
            list($storedUsername, $storedPassword, $storedRole) = explode('|', $user);
            if ($storedUsername === $username) {
                $userExists = true;
                break;
            }
        }

        if ($userExists) {
            $registerMessage = ($language == 'es') 
                ? "El nombre de usuario ya está en uso." 
                : "Username is already taken.";
        } else {
            // Guardar el nuevo usuario en el archivo
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña
            $role = $isAdmin ? 'admin' : 'user';
            $userData = $username . '|' . $hashedPassword . '|' . $role . "\n";
            file_put_contents($usersFile, $userData, FILE_APPEND);
            $registerMessage = ($language == 'es') 
                ? "Registro exitoso. Ahora puedes iniciar sesión." 
                : "Registration successful. You can now log in.";
        }
    }
}

// Actualizar las cookies de idioma y estilo si se envían nuevos valores desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language']) && isset($_POST['style'])) {
    // Eliminar las cookies existentes si es necesario
    setcookie('language', $_POST['language'], time() + (30 * 24 * 60 * 60), '/');
    setcookie('style', $_POST['style'], time() + (30 * 24 * 60 * 60), '/');

    // Actualizar las variables locales
    $language = $_POST['language'];
    $style = $_POST['style'];
}

?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($language == 'es') ? 'Registro de Usuario' : 'User Registration'; ?></title>
    <!-- Carga dinámica del estilo según las preferencias guardadas en las cookies -->
    <link rel="stylesheet" href="../css/style-<?php echo $style; ?>.css">
</head>
<body>
    <h2><?php echo ($language == 'es') ? 'Formulario de Registro' : 'Registration Form'; ?></h2>

    <header>
        <div class="header-links">
            <!-- Botón Ajustes -->
            <form method="POST" action="preferencias.php">
                <input type="hidden" name="source" value="registro"> <!-- Campo oculto con el origen -->
                <button type="submit" class="btn-link">
                    <img src="../img/settings-icon-<?php echo $style == 'dark' ? 'oscuro' : 'claro'; ?>.png" 
                         alt="<?php echo $language == 'en' ? 'Settings' : 'Ajustes'; ?>" 
                         class="icono">
                    <?php echo $language == 'en' ? 'Settings' : 'Ajustes'; ?>
                </button>
            </form>
        </div>
    </header>

    <?php if (!empty($registerMessage)) { echo "<p>$registerMessage</p>"; } ?>

    <form method="POST" action="">
        <label for="username"><?php echo ($language == 'es') ? 'Nombre de usuario:' : 'Username:'; ?></label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="password"><?php echo ($language == 'es') ? 'Contraseña:' : 'Password:'; ?></label>
        <input type="password" id="password" name="password" required>
        <br>

        <label for="confirm_password"><?php echo ($language == 'es') ? 'Confirmar contraseña:' : 'Confirm Password:'; ?></label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>

        <label for="isAdmin"><?php echo ($language == 'es') ? '¿Eres administrador?' : 'Are you an administrator?'; ?></label>
        <input type="checkbox" id="isAdmin" name="isAdmin">
        <br>

        <button type="submit"><?php echo ($language == 'es') ? 'Registrar' : 'Register'; ?></button>
    </form>

    <p>
        <?php echo ($language == 'es') 
            ? '¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>.' 
            : 'Already have an account? <a href="login.php">Log in</a>.'; ?>
    </p>
</body>
</html>
