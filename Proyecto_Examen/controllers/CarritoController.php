<?php
require_once '../models/CarritoModel.php';

class CarritoController {
    private $carritoModel;

    public function __construct() {
        $this->carritoModel = new CarritoModel();
    }

    // Agregar producto al carrito
    public function agregarAlCarrito() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Recuperamos el carrito desde la cookie
        $carrito = $this->carritoModel->obtenerCarrito();

        // Verificamos si el producto fue agregado
        if (isset($_POST['agregar'])) {
            // Usamos el nombre de cada producto para obtener el precio desde los campos ocultos
            $nombre = $_POST['nombre_' . $_POST['agregar']];
            $precio = $_POST['precio_' . $_POST['agregar']];

            // Agregar el producto al carrito
            $carrito = $this->carritoModel->agregarProducto($nombre, $precio, $carrito);

            // Guardamos el carrito actualizado en la cookie
            $this->carritoModel->guardarCarrito($carrito);

            // Redirigimos para evitar el reenvío del formulario
            header("Location: ../views/carrito.php");
            exit;
        }
    }

    // Limpiar el carrito
    public function limpiarCarrito() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Limpiamos la cookie del carrito
        $this->carritoModel->limpiarCarrito();

        // Redirigimos a la página del carrito
        header("Location: ../views/carrito.php");
        exit;
    }
}
?>
