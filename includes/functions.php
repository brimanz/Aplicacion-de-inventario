<?php
/**
 * Funciones CRUD completas para el sistema de inventario
 * Ubicación: includes/functions.php
 */

require_once __DIR__ . '/../config/database.php';

function getDatabase() {
    $db = new Database();
    return $db->getConnection();
}

// ============================================
// FUNCIONES DE PRODUCTOS
// ============================================

function getAllProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT p.*, c.nombre as categoria, m.nombre as modelo, pr.nombre as proveedor 
                            FROM productos p 
                            LEFT JOIN categorias c ON p.categoria_id = c.id 
                            LEFT JOIN modelos m ON p.modelo_id = m.id 
                            LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
                            ORDER BY p.created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProductById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT p.*, c.nombre as categoria_nombre, m.nombre as modelo_nombre, pr.nombre as proveedor_nombre 
                            FROM productos p 
                            LEFT JOIN categorias c ON p.categoria_id = c.id 
                            LEFT JOIN modelos m ON p.modelo_id = m.id 
                            LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
                            WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addProduct($nombre, $descripcion, $categoria_id, $modelo_id, $proveedor_id, $precio, $stock, $codigo, $imagen = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, categoria_id, modelo_id, proveedor_id, precio, stock, codigo, imagen) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $descripcion, $categoria_id, $modelo_id, $proveedor_id, $precio, $stock, $codigo, $imagen]);
}

function updateProduct($id, $nombre, $descripcion, $categoria_id, $modelo_id, $proveedor_id, $precio, $stock, $codigo, $imagen = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, categoria_id=?, modelo_id=?, proveedor_id=?, precio=?, stock=?, codigo=?, imagen=? WHERE id=?");
    return $stmt->execute([$nombre, $descripcion, $categoria_id, $modelo_id, $proveedor_id, $precio, $stock, $codigo, $imagen, $id]);
}

function deleteProduct($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    return $stmt->execute([$id]);
}

function countProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos");
    $stmt->execute();
    return $stmt->fetch()['total'];
}

function getTotalInventoryValue() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT SUM(precio * stock) as total FROM productos");
    $stmt->execute();
    return $stmt->fetch()['total'] ?? 0;
}

function countLowStockProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE stock <= stock_minimo");
    $stmt->execute();
    return $stmt->fetch()['total'];
}

function countOutOfStockProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE stock = 0");
    $stmt->execute();
    return $stmt->fetch()['total'];
}

// ============================================
// FUNCIONES DE CATEGORÍAS
// ============================================

function getAllCategories() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT c.*, COUNT(p.id) as total_productos 
                            FROM categorias c 
                            LEFT JOIN productos p ON c.id = p.categoria_id 
                            GROUP BY c.id ORDER BY c.nombre");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCategoryById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addCategory($nombre, $descripcion = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
    return $stmt->execute([$nombre, $descripcion]);
}

function updateCategory($id, $nombre, $descripcion = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE categorias SET nombre=?, descripcion=? WHERE id=?");
    return $stmt->execute([$nombre, $descripcion, $id]);
}

function deleteCategory($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============================================
// FUNCIONES DE PROVEEDORES
// ============================================

function getAllProviders() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT pr.*, COUNT(p.id) as total_productos 
                            FROM proveedores pr 
                            LEFT JOIN productos p ON pr.id = p.proveedor_id 
                            GROUP BY pr.id ORDER BY pr.nombre");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProviderById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addProvider($nombre, $contacto, $telefono, $email, $direccion, $ruc) {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email, direccion, ruc) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $contacto, $telefono, $email, $direccion, $ruc]);
}

function updateProvider($id, $nombre, $contacto, $telefono, $email, $direccion, $ruc) {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE proveedores SET nombre=?, contacto=?, telefono=?, email=?, direccion=?, ruc=? WHERE id=?");
    return $stmt->execute([$nombre, $contacto, $telefono, $email, $direccion, $ruc, $id]);
}

function deleteProvider($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============================================
// FUNCIONES DE MODELOS
// ============================================

function getAllModels() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT m.*, COUNT(p.id) as total_productos 
                            FROM modelos m 
                            LEFT JOIN productos p ON m.id = p.modelo_id 
                            GROUP BY m.id ORDER BY m.nombre");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getModelById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM modelos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addModel($nombre, $marca, $descripcion = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO modelos (nombre, marca, descripcion) VALUES (?, ?, ?)");
    return $stmt->execute([$nombre, $marca, $descripcion]);
}

function updateModel($id, $nombre, $marca, $descripcion = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE modelos SET nombre=?, marca=?, descripcion=? WHERE id=?");
    return $stmt->execute([$nombre, $marca, $descripcion, $id]);
}

function deleteModel($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM modelos WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============================================
// FUNCIONES DE MOVIMIENTOS
// ============================================

function getAllMovements($tipo = null) {
    $conn = getDatabase();
    if ($tipo) {
        $stmt = $conn->prepare("SELECT m.*, p.nombre as producto, p.codigo, u.nombre as usuario 
                                FROM movimientos m 
                                JOIN productos p ON m.producto_id = p.id 
                                LEFT JOIN usuarios u ON m.usuario_id = u.id 
                                WHERE m.tipo = ? 
                                ORDER BY m.fecha DESC");
        $stmt->execute([$tipo]);
    } else {
        $stmt = $conn->prepare("SELECT m.*, p.nombre as producto, p.codigo, u.nombre as usuario 
                                FROM movimientos m 
                                JOIN productos p ON m.producto_id = p.id 
                                LEFT JOIN usuarios u ON m.usuario_id = u.id 
                                ORDER BY m.fecha DESC");
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

function registerMovement($producto_id, $tipo, $cantidad, $motivo = '', $referencia = '', $usuario_id = null) {
    $conn = getDatabase();
    
    // Obtener stock actual
    $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();
    
    if (!$producto) return false;
    
    $stock_anterior = $producto['stock'];
    $stock_nuevo = $stock_anterior;
    
    // Calcular nuevo stock según tipo
    if ($tipo === 'entrada') {
        $stock_nuevo = $stock_anterior + $cantidad;
    } elseif ($tipo === 'salida') {
        if ($stock_anterior < $cantidad) return false; // Stock insuficiente
        $stock_nuevo = $stock_anterior - $cantidad;
    } elseif ($tipo === 'ajuste') {
        $stock_nuevo = $cantidad; // En ajuste, la cantidad es el nuevo stock
    }
    
    // Registrar movimiento
    $stmt = $conn->prepare("INSERT INTO movimientos (producto_id, tipo, cantidad, stock_anterior, stock_nuevo, motivo, referencia, usuario_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$producto_id, $tipo, $cantidad, $stock_anterior, $stock_nuevo, $motivo, $referencia, $usuario_id]);
    
    if ($result) {
        // Actualizar stock del producto
        $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$stock_nuevo, $producto_id]);
    }
    
    return $result;
}

// ============================================
// FUNCIONES DE HISTORIAL
// ============================================

function getAllHistory() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT h.*, u.nombre as usuario 
                            FROM historial h 
                            LEFT JOIN usuarios u ON h.usuario_id = u.id 
                            ORDER BY h.created_at DESC LIMIT 200");
    $stmt->execute();
    return $stmt->fetchAll();
}

function registerHistory($usuario_id, $accion, $modulo, $descripcion, $ip = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO historial (usuario_id, accion, modulo, descripcion, ip_address) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$usuario_id, $accion, $modulo, $descripcion, $ip]);
}

// ============================================
// FUNCIONES DE USUARIOS
// ============================================

function getAllUsers() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM usuarios ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addUser($username, $password, $nombre, $email, $rol = 'usuario') {
    $conn = getDatabase();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, nombre, email, rol) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$username, $hashedPassword, $nombre, $email, $rol]);
}

function updateUser($id, $nombre, $email, $rol, $password = null) {
    $conn = getDatabase();
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, rol=?, password=? WHERE id=?");
        return $stmt->execute([$nombre, $email, $rol, $hashedPassword, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?");
        return $stmt->execute([$nombre, $email, $rol, $id]);
    }
}

function deleteUser($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ? AND id != 1"); // No permitir eliminar admin principal
    return $stmt->execute([$id]);
}

function toggleUserStatus($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ? AND id != 1");
    return $stmt->execute([$id]);
}

// ============================================
// FUNCIONES DE EMPRESA
// ============================================

function getCompanyInfo() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM empresa LIMIT 1");
    $stmt->execute();
    return $stmt->fetch();
}

function updateCompanyInfo($nombre, $ruc, $direccion, $telefono, $email, $logo = '') {
    $conn = getDatabase();
    // Verificar si existe
    $stmt = $conn->prepare("SELECT id FROM empresa LIMIT 1");
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if ($exists) {
        $stmt = $conn->prepare("UPDATE empresa SET nombre=?, ruc=?, direccion=?, telefono=?, email=?, logo=? WHERE id=?");
        return $stmt->execute([$nombre, $ruc, $direccion, $telefono, $email, $logo, $exists['id']]);
    } else {
        $stmt = $conn->prepare("INSERT INTO empresa (nombre, ruc, direccion, telefono, email, logo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $ruc, $direccion, $telefono, $email, $logo]);
    }
}

// ============================================
// FUNCIONES DE RESPALDO
// ============================================

function createDatabaseBackup() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $sql = "-- Respaldo de Base de Datos\n";
    $sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        $sql .= "-- Estructura de tabla: $table\n";
        $stmt = $conn->prepare("SHOW CREATE TABLE $table");
        $stmt->execute();
        $create = $stmt->fetch();
        $sql .= $create[1] . ";\n\n";
        
        $sql .= "-- Datos de tabla: $table\n";
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $values = array_map(function($val) use ($conn) {
                return $val === null ? 'NULL' : "'" . $conn->quote($val) . "'";
            }, array_values($row));
            $sql .= "INSERT INTO $table VALUES (" . implode(', ', $values) . ");\n";
        }
        $sql .= "\n";
    }
    
    return $sql;
}

function downloadBackup() {
    $sql = createDatabaseBackup();
    $filename = 'respaldo_' . date('Y-m-d_H-i-s') . '.sql';
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $sql;
    exit();
}
?>