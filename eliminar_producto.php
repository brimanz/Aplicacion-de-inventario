<?php
/**
 * Eliminar Producto
 * Ubicación: eliminar_producto.php
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: productos.php");
    exit();
}

// Verificar que el producto existe
$producto = getProductById($id);
if (!$producto) {
    header("Location: productos.php?error=1");
    exit();
}

// Eliminar el producto
if (deleteProduct($id)) {
    header("Location: productos.php?deleted=1");
} else {
    header("Location: productos.php?error=1");
}
exit();
?>