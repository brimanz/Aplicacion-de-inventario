<?php
/**
 * Dashboard Principal
 * Ubicación: dashboard.php
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

// Obtener estadísticas
$totalProductos = countProducts();
$valorInventario = getTotalInventoryValue();
$productosBajoStock = countLowStockProducts();
$productosAgotados = countOutOfStockProducts();

// Obtener productos recientes
$conn = getDatabase();
$stmt = $conn->prepare("SELECT * FROM productos ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$productosRecientes = $stmt->fetchAll();

$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Inventario</title>
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
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="agregar_producto.php">Agregar</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <!-- Banner Principal -->
    <div class="banner">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
        <p>Gestiona tu inventario de manera eficiente y profesional</p>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard">
        <!-- Tarjetas de Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">📦</div>
                <h3>Total Productos</h3>
                <div class="value"><?php echo $totalProductos; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="icon">💰</div>
                <h3>Valor del Inventario</h3>
                <div class="value">$<?php echo number_format($valorInventario, 2); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="icon">️</div>
                <h3>Stock Bajo</h3>
                <div class="value"><?php echo $productosBajoStock; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="icon"></div>
                <h3>Agotados</h3>
                <div class="value"><?php echo $productosAgotados; ?></div>
            </div>
        </div>

        <!-- Productos Recientes -->
        <div class="products-section">
            <div class="section-header">
                <h2>📋 Productos Recientes</h2>
                <a href="productos.php" class="btn btn-secondary">Ver Todos →</a>
            </div>
            
            <?php if (empty($productosRecientes)): ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <p>No hay productos registrados aún.</p>
                    <a href="agregar_producto.php">Agregar el primer producto</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productosRecientes as $producto): ?>
                            <tr>
                                <td>#<?php echo $producto['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($producto['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?> unidades</td>
                                <td>
                                    <?php if ($producto['stock'] > 10): ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php elseif ($producto['stock'] > 0): ?>
                                        <span class="badge badge-warning">Stock Bajo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Agotado</span>
                                    <?php endif; ?>
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