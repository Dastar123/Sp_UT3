<?php
session_start();
require_once '../controllers/CarritoController.php';

$carritoController = new CarritoController();

// Agregar productos al carrito
if (isset($_POST['agregar'])) {
    $carritoController->agregarAlCarrito();
}

// Limpiar el carrito si se presiona el botón
if (isset($_POST['limpiar'])) {
    $carritoController->limpiarCarrito();
}

 

// Recuperamos el carrito desde la cookie (si existe)
$carrito = isset($_COOKIE['carritoCookie']) ? json_decode($_COOKIE['carritoCookie'], true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
</head>
<body>
    <h1>Carrito de Compras</h1>

    <form method="POST" action="../index.php">
        <button name="volverIndice">Volver a Menú</button>
    </form>
    
    <!-- Formulario de cierre de sesión -->
    <form method="POST" action="logout.php">
    <button type="submit" name="limpiar">Cerrar Sesión</button>
    </form>



    <h2>Productos Disponibles</h2>
    <form method="POST">
        <!-- Producto: Ratón -->
        <button type="submit" name="agregar" value="Ratón">
            Agregar Ratón (20€)
        </button>
        <input type="hidden" name="nombre_Ratón" value="Ratón">
        <input type="hidden" name="precio_Ratón" value="20">

        <!-- Producto: Teclado -->
        <button type="submit" name="agregar" value="Teclado">
            Agregar Teclado (40€)
        </button>
        <input type="hidden" name="nombre_Teclado" value="Teclado">
        <input type="hidden" name="precio_Teclado" value="40">

        <!-- Producto: Monitor -->
        <button type="submit" name="agregar" value="Monitor">
            Agregar Monitor (150€)
        </button>
        <input type="hidden" name="nombre_Monitor" value="Monitor">
        <input type="hidden" name="precio_Monitor" value="150">

        <!-- Producto: Auriculares -->
        <button type="submit" name="agregar" value="Auriculares">
            Agregar Auriculares (30€)
        </button>
        <input type="hidden" name="nombre_Auriculares" value="Auriculares">
        <input type="hidden" name="precio_Auriculares" value="30">
    </form>

    <h2>Resumen del Carrito</h2>
    <?php if (count($carrito) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio por unidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($carrito as $item):
                    $productoTotal = $item['precio'] * $item['cantidad'];
                    $total += $productoTotal;
                ?>
                   <tr>
                        <td><?= $item['nombre'] ?> &nbsp;</td>
                        <td>&nbsp; <?= $item['cantidad'] ?></td>
                        <td>&nbsp; <?= $item['precio'] ?> €</td>
                        <td>&nbsp; <?= $productoTotal ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total: <?= $total ?>€</h3>
    <?php else: ?>
        <p>No hay productos en el carrito.</p>
    <?php endif; ?>

    <form method="POST">
        <button name="limpiar">Limpiar Carrito</button>
    </form>
</body>
</html>
