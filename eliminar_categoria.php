<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    deleteCategory($id);
    registerHistory($_SESSION['user_id'], 'Eliminar', 'Categorías', "Categoría eliminada ID: $id", $_SERVER['REMOTE_ADDR']);
}
header("Location: categorias.php?deleted=1");
exit();
?>