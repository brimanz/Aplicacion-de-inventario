<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    deleteModel($id);
    registerHistory($_SESSION['user_id'], 'Eliminar', 'Modelos', "Modelo eliminado ID: $id", $_SERVER['REMOTE_ADDR']);
}
header("Location: modelos.php?deleted=1");
exit();
?>