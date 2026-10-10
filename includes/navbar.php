<?php
// includes/navbar.php - Navbar reutilizable para todo el sistema

if (!isset($usuario)) {
    $usuario = getCurrentUser();
}

// Contar productos con stock bajo para el badge
$productosBajoStock = countLowStockProducts();
?>

<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">
        📦 Sistema <span>Inventario</span>
    </a>
    <ul class="nav-menu">
        <li><span class="nav-user">👋 <?php echo htmlspecialchars($usuario['nombre']); ?></span></li>
        
        <!-- REGISTRO -->
        <li>
            <a href="#">📝 Registro <span class="arrow">▼</span></a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Módulo de Registro</li>
                <li><a href="productos.php"><span class="icon">📦</span> Productos</a></li>
                <li><a href="categorias.php"><span class="icon">🏷️</span> Categorías</a></li>
                <li><a href="proveedores.php"><span class="icon"></span> Proveedores</a></li>
                <li><a href="modelos.php"><span class="icon">🔧</span> Modelos</a></li>
            </ul>
        </li>
        
        <!-- MOVIMIENTOS -->
        <li>
            <a href="#">🔄 Movimientos <span class="arrow">▼</span></a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Gestión de Movimientos</li>
                <li><a href="movimientos.php?tipo=entrada"><span class="icon">📥</span> Entradas / Compras</a></li>
                <li><a href="movimientos.php?tipo=salida"><span class="icon">📤</span> Salidas / Ventas</a></li>
                <li><a href="movimientos.php?tipo=ajuste"><span class="icon">️</span> Ajustes de Stock</a></li>
                <li class="dropdown-divider"></li>
                <li><a href="movimientos.php"><span class="icon"></span> Ver Todos</a></li>
            </ul>
        </li>
        
        <!-- HISTORIAL -->
        <li>
            <a href="historial.php"> Historial</a>
        </li>
        
        <!-- REPORTES -->
        <li>
            <a href="reportes.php"> Reportes</a>
        </li>
        
        <!-- CONFIGURACIÓN -->
        <li>
            <a href="#">⚙️ Configuración <span class="arrow">▼</span></a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Panel de Configuración</li>
                <li><a href="usuarios.php"><span class="icon">👥</span> Gestión de Usuarios</a></li>
                <li><a href="respaldo.php"><span class="icon">💾</span> Respaldo BD</a></li>
                <li><a href="manual.php"><span class="icon"></span> Manual de Usuario</a></li>
                <li><a href="empresa.php"><span class="icon">🏢</span> Información Empresa</a></li>
            </ul>
        </li>
        
        <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
</nav>