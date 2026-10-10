-- ============================================
-- SISTEMA DE INVENTARIO COMPLETO
-- Base de datos actualizada con todos los módulos
-- ============================================

CREATE DATABASE IF NOT EXISTS sistema_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_inventario;

-- ============================================
-- TABLA: USUARIOS
-- ============================================
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: CATEGORÍAS
-- ============================================
DROP TABLE IF EXISTS categorias;
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: PROVEEDORES
-- ============================================
DROP TABLE IF EXISTS proveedores;
CREATE TABLE proveedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    ruc VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: MODELOS
-- ============================================
DROP TABLE IF EXISTS modelos;
CREATE TABLE modelos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    marca VARCHAR(100),
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: PRODUCTOS
-- ============================================
DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria_id INT,
    modelo_id INT,
    proveedor_id INT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    imagen VARCHAR(255),
    codigo VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (modelo_id) REFERENCES modelos(id) ON DELETE SET NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: MOVIMIENTOS (Entradas/Salidas/Ajustes)
-- ============================================
DROP TABLE IF EXISTS movimientos;
CREATE TABLE movimientos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT NOT NULL,
    tipo ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    stock_anterior INT NOT NULL,
    stock_nuevo INT NOT NULL,
    motivo VARCHAR(255),
    referencia VARCHAR(100),
    usuario_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: HISTORIAL (Log de acciones)
-- ============================================
DROP TABLE IF EXISTS historial;
CREATE TABLE historial (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(255) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    descripcion TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: CONFIGURACIÓN EMPRESA
-- ============================================
DROP TABLE IF EXISTS empresa;
CREATE TABLE empresa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    ruc VARCHAR(20),
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Usuario admin (contraseña: admin123)
INSERT INTO usuarios (username, password, nombre, email, rol) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@sistema.com', 'admin');

-- Empresa por defecto
INSERT INTO empresa (nombre, ruc, direccion, telefono, email) VALUES 
('Mi Empresa S.A.', '1234567890001', 'Av. Principal 123', '0991234567', 'contacto@miempresa.com');

-- Categorías de ejemplo
INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónica', 'Dispositivos y equipos electrónicos'),
('Accesorios', 'Accesorios y complementos'),
('Oficina', 'Artículos de oficina'),
('Hogar', 'Artículos para el hogar');

-- Proveedores de ejemplo
INSERT INTO proveedores (nombre, contacto, telefono, email, ruc) VALUES
('Tech Solutions S.A.', 'Juan Pérez', '0991111111', 'ventas@techsolutions.com', '1234567890001'),
('Distribuidora ABC', 'María López', '0992222222', 'contacto@abc.com', '9876543210001'),
('Importadora Global', 'Carlos Ruiz', '0993333333', 'info@global.com', '5555555555001');

-- Modelos de ejemplo
INSERT INTO modelos (nombre, marca, descripcion) VALUES
('Pavilion 15', 'HP', 'Laptop HP Pavilion 15 pulgadas'),
('Galaxy S23', 'Samsung', 'Smartphone Samsung Galaxy S23'),
('ThinkPad X1', 'Lenovo', 'Laptop Lenovo ThinkPad X1 Carbon');

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, categoria_id, modelo_id, proveedor_id, precio, stock, codigo) VALUES
('Laptop HP Pavilion', 'Laptop con procesador Intel i7, 16GB RAM', 1, 1, 1, 899.99, 15, 'LAP-001'),
('Mouse Inalámbrico', 'Mouse ergonómico con batería recargable', 2, NULL, 2, 29.99, 50, 'MOU-001'),
('Teclado Mecánico', 'Teclado RGB con switches Cherry MX', 2, NULL, 2, 79.99, 8, 'TEC-001'),
('Monitor 27 pulgadas', 'Monitor 4K IPS con HDR', 1, NULL, 1, 349.99, 3, 'MON-001'),
('Webcam HD', 'Cámara web 1080p con micrófono', 2, NULL, 3, 59.99, 0, 'WEB-001');