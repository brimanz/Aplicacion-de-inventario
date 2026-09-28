<?php
/**
 * Agregar Producto
 * Ubicación: agregar_producto.php
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $imagen = trim($_POST['imagen'] ?? '');
    
    // Validaciones
    if (empty($nombre) || empty($categoria) || $precio <= 0) {
        $mensaje = "Por favor, complete todos los campos obligatorios correctamente";
        $tipoMensaje = 'danger';
    } else {
        if (addProduct($nombre, $descripcion, $categoria, $precio, $stock, $imagen)) {
            header("Location: productos.php?created=1");
            exit();
        } else {
            $mensaje = "Error al agregar el producto. Intente nuevamente.";
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
    <title>Agregar Producto - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar">
        <a href="dashboard.php" class="navbar-brand">
            📦 Sistema <span>Inventario</span>
        </a>
        <ul class="nav-menu">
            <li><span class="nav-user">👋 <?php echo htmlspecialchars($usuario['nombre']); ?></span></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="agregar_producto.php" class="active">Agregar</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <!-- Banner -->
    <div class="banner">
        <h1>Agregar Nuevo Producto</h1>
        <p>Complete la información del producto</p>
    </div>

    <!-- Formulario -->
    <div class="form-container">
        <h2> Información del Producto</h2>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre del Producto *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Laptop HP Pavilion" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" placeholder="Descripción detallada del producto..."></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="categoria">Categoría *</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Ej: Electrónica" required>
                </div>
                
                <div class="form-group">
                    <label for="imagen">URL de Imagen</label>
                    <input type="url" id="imagen" name="imagen" placeholder="https://...">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio ($) *</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0" placeholder="0.00" required>
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock (unidades) *</label>
                    <input type="number" id="stock" name="stock" min="0" placeholder="0" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar Producto</button>
                <a href="productos.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 Sistema de Inventario | Desarrollado con <span>♥</span> usando PHP + MySQL</p>
    </div>

    <script src="js/app.js"></script>
</body>
</html>