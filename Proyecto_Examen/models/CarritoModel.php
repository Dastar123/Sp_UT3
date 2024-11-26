<?php
class CarritoModel {

    // Ruta del nombre de la cookie
    private $carritoCookieName = 'carritoCookie';

    // Recuperar los productos del carrito desde la cookie
    public function obtenerCarrito() {
        if (isset($_COOKIE[$this->carritoCookieName])) {
            return json_decode($_COOKIE[$this->carritoCookieName], true);
        }
        return [];  // Si no existe la cookie, inicializamos un carrito vacío
    }

    // Guardar el carrito en la cookie
    public function guardarCarrito($carrito) {
        setcookie($this->carritoCookieName, json_encode($carrito), time() + 3600, "/");
    }

    // Agregar un producto al carrito
    public function agregarProducto($nombre, $precio, $carrito) {
        $productoEncontrado = false;

        // Buscar si el producto ya está en el carrito
        foreach ($carrito as &$item) {
            if ($item['nombre'] == $nombre) {
                $item['cantidad']++;
                $productoEncontrado = true;
                break;
            }
        }

        // Si no se encuentra el producto, lo agregamos con cantidad 1
        if (!$productoEncontrado) {
            $carrito[] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => 1
            ];
        }

        return $carrito;
    }

    // Limpiar el carrito (eliminar la cookie)
    public function limpiarCarrito() {
        setcookie($this->carritoCookieName, '', time() - 3600, "/");
    }
}
?>
