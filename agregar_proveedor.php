<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ruc = trim($_POST['ruc'] ?? '');
    
    if (empty($nombre)) {
        $mensaje = "El nombre es obligatorio";
        $tipoMensaje = 'danger';
    } else {
        if (addProvider($nombre, $contacto, $telefono, $email, $direccion, $ruc)) {
            registerHistory($_SESSION['user_id'], 'Crear', 'Proveedores', "Proveedor creado: $nombre", $_SERVER['REMOTE_ADDR']);
            header("Location: proveedores.php?created=1");
            exit();
        } else {
            $mensaje = "Error al crear el proveedor";
            $tipoMensaje = 'danger';
        }
    }
}

$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="banner">
        <h1>Nuevo Proveedor</h1>
        <p>Registra un nuevo proveedor</p>
    </div>

    <div class="form-container">
        <h2>🏢 Información del Proveedor</h2>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre / Razón Social *</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contacto">Persona de Contacto</label>
                    <input type="text" id="contacto" name="contacto">
                </div>
                <div class="form-group">
                    <label for="ruc">RUC / NIT</label>
                    <input type="text" id="ruc" name="ruc">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar Proveedor</button>
                <a href="proveedores.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="js/app.js"></script>
</body>
</html>