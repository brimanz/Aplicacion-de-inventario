<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$categorias = getAllCategories();
$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="banner">
        <h1>Gestión de Categorías</h1>
        <p>Organiza tus productos por categorías</p>
    </div>

    <div class="dashboard">
        <div class="products-section">
            <div class="module-header">
                <h2>🏷️ Categorías Registradas (<?php echo count($categorias); ?>)</h2>
                <a href="agregar_categoria.php" class="btn btn-primary">+ Nueva Categoría</a>
            </div>
            
            <?php if (empty($categorias)): ?>
                <div class="empty-state">
                    <div class="icon">️</div>
                    <p>No hay categorías registradas.</p>
                    <a href="agregar_categoria.php">Crear la primera categoría</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Productos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td>#<?php echo $cat['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cat['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($cat['descripcion'] ?? '-'); ?></td>
                                <td><span class="badge badge-info"><?php echo $cat['total_productos']; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editar_categoria.php?id=<?php echo $cat['id']; ?>" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                        <a href="eliminar_categoria.php?id=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta categoría?')">🗑️ Eliminar</a>
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

    <script src="js/app.js"></script>
</body>
</html>