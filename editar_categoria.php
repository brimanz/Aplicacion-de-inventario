<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: categorias.php"); exit(); }

$categoria = getCategoryById($id);
if (!$categoria) { header("Location: categorias.php"); exit(); }

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    
    if (empty($nombre)) {
        $mensaje = "El nombre es obligatorio";
        $tipoMensaje = 'danger';
    } else {
        if (updateCategory($id, $nombre, $descripcion)) {
            registerHistory($_SESSION['user_id'], 'Actualizar', 'Categorías', "Categoría actualizada: $nombre", $_SERVER['REMOTE_ADDR']);
            header("Location: categorias.php?updated=1");
            exit();
        } else {
            $mensaje = "Error al actualizar";
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
    <title>Editar Categoría - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="banner">
        <h1>Editar Categoría</h1>
        <p>Modifica la información de la categoría</p>
    </div>

    <div class="form-container">
        <h2>✏️ Editar: <?php echo htmlspecialchars($categoria['nombre']); ?></h2>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Actualizar</button>
                <a href="categorias.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="js/app.js"></script>
</body>
</html>