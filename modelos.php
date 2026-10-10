<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$modelos = getAllModels();
$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelos - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="banner">
        <h1>Gestión de Modelos</h1>
        <p>Administra los modelos de productos</p>
    </div>

    <div class="dashboard">
        <div class="products-section">
            <div class="module-header">
                <h2>🔧 Modelos Registrados (<?php echo count($modelos); ?>)</h2>
                <a href="agregar_modelo.php" class="btn btn-primary">+ Nuevo Modelo</a>
            </div>
            
            <?php if (empty($modelos)): ?>
                <div class="empty-state">
                    <div class="icon">🔧</div>
                    <p>No hay modelos registrados.</p>
                    <a href="agregar_modelo.php">Crear el primero</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Descripción</th>
                                <th>Productos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modelos as $mod): ?>
                            <tr>
                                <td>#<?php echo $mod['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($mod['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($mod['marca'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($mod['descripcion'] ?? '-'); ?></td>
                                <td><span class="badge badge-info"><?php echo $mod['total_productos']; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editar_modelo.php?id=<?php echo $mod['id']; ?>" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                        <a href="eliminar_modelo.php?id=<?php echo $mod['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este modelo?')">🗑️</a>
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