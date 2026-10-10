<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$proveedores = getAllProviders();
$usuario = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="banner">
        <h1>Gestión de Proveedores</h1>
        <p>Administra los proveedores de tu inventario</p>
    </div>

    <div class="dashboard">
        <div class="products-section">
            <div class="module-header">
                <h2>🏢 Proveedores Registrados (<?php echo count($proveedores); ?>)</h2>
                <a href="agregar_proveedor.php" class="btn btn-primary">+ Nuevo Proveedor</a>
            </div>
            
            <?php if (empty($proveedores)): ?>
                <div class="empty-state">
                    <div class="icon">🏢</div>
                    <p>No hay proveedores registrados.</p>
                    <a href="agregar_proveedor.php">Registrar el primero</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>RUC</th>
                                <th>Productos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proveedores as $prov): ?>
                            <tr>
                                <td>#<?php echo $prov['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($prov['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($prov['contacto'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($prov['telefono'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($prov['email'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($prov['ruc'] ?? '-'); ?></td>
                                <td><span class="badge badge-info"><?php echo $prov['total_productos']; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editar_proveedor.php?id=<?php echo $prov['id']; ?>" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                        <a href="eliminar_proveedor.php?id=<?php echo $prov['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este proveedor?')">🗑️</a>
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