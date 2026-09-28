<?php
/**
 * Funciones CRUD para el sistema de inventario
 * Ubicación: includes/functions.php
 */

// Incluir la clase de base de datos
require_once __DIR__ . '/../config/database.php';

/**
 * Obtiene una instancia de conexión a la base de datos
 * @return PDO
 */
function getDatabase() {
    $db = new Database();
    return $db->getConnection();
}

// ============================================
// FUNCIONES DE PRODUCTOS
// ============================================

/**
 * Obtener todos los productos
 * @return array
 */
function getAllProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM productos ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Obtener producto por ID
 * @param int $id
 * @return array|false
 */
function getProductById($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Agregar un nuevo producto
 * @param string $nombre
 * @param string $descripcion
 * @param string $categoria
 * @param float $precio
 * @param int $stock
 * @param string $imagen
 * @return bool
 */
function addProduct($nombre, $descripcion, $categoria, $precio, $stock, $imagen = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, categoria, precio, stock, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $descripcion, $categoria, $precio, $stock, $imagen]);
}

/**
 * Actualizar un producto existente
 * @param int $id
 * @param string $nombre
 * @param string $descripcion
 * @param string $categoria
 * @param float $precio
 * @param int $stock
 * @param string $imagen
 * @return bool
 */
function updateProduct($id, $nombre, $descripcion, $categoria, $precio, $stock, $imagen = '') {
    $conn = getDatabase();
    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, categoria=?, precio=?, stock=?, imagen=? WHERE id=?");
    return $stmt->execute([$nombre, $descripcion, $categoria, $precio, $stock, $imagen, $id]);
}

/**
 * Eliminar un producto
 * @param int $id
 * @return bool
 */
function deleteProduct($id) {
    $conn = getDatabase();
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Contar total de productos
 * @return int
 */
function countProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
}

/**
 * Calcular valor total del inventario
 * @return float
 */
function getTotalInventoryValue() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT SUM(precio * stock) as total FROM productos");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

/**
 * Obtener productos con stock bajo (menos de 10 unidades)
 * @return int
 */
function countLowStockProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE stock < 10");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
}

/**
 * Obtener productos agotados
 * @return int
 */
function countOutOfStockProducts() {
    $conn = getDatabase();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE stock = 0");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
}

/**
 * Buscar productos por nombre o categoría
 * @param string $search
 * @return array
 */
function searchProducts($search) {
    $conn = getDatabase();
    $searchTerm = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? ORDER BY nombre ASC");
    $stmt->execute([$searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}
?>