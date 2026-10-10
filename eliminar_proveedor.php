<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    deleteProvider($id);
    registerHistory($_SESSION['user_id'], 'Eliminar', 'Proveedores', "Proveedor eliminado ID: $id", $_SERVER['REMOTE_ADDR']);
}
header("Location: proveedores.php?deleted=1");
exit();
?>