<?php
/**
 * Lista de Productos
 * Ubicación: productos.php
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$productos = getAllProducts();
$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Sistema de Inventario</title>
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
            <li><a href="productos.php" class="active">Productos</a></li>
            <li><a href="agregar_producto.php">Agregar</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <!-- Banner -->
    <div class="banner">
        <h1>Gestión de Productos</h1>
        <p>Administra todos los productos de tu inventario</p>
    </div>

    <!-- Contenido -->
    <div class="dashboard">
        <div class="products-section">
            <div class="section-header">
                <h2>📋 Todos los Productos (<?php echo count($productos); ?>)</h2>
                <a href="agregar_producto.php" class="btn btn-primary">+ Agregar Producto</a>
            </div>
            
            <?php if (empty($productos)): ?>
                <div class="empty-state">
                    <div class="icon"></div>
                    <p>No hay productos registrados.</p>
                    <a href="agregar_producto.php">Agregar el primer producto</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>#<?php echo $producto['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($producto['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 50)) . (strlen($producto['descripcion']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td>
                                    <?php if ($producto['stock'] > 10): ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php elseif ($producto['stock'] > 0): ?>
                                        <span class="badge badge-warning">Stock Bajo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Agotado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-secondary btn-sm">️ Editar</a>
                                        <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm">️ Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 Sistema de Inventario | Desarrollado con <span>♥</span> usando PHP + MySQL</p>
    </div>

    <script src="js/app.js"></script>
</body>
</html>