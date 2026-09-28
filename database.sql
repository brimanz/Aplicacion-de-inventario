-- Sistema de Inventario - Base de Datos
-- Ejecutar este script en phpMyAdmin o MySQL Workbench

CREATE DATABASE IF NOT EXISTS sistema_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_inventario;

-- ============================================
-- TABLA DE USUARIOS
-- ============================================
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA DE PRODUCTOS
-- ============================================
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria VARCHAR(50),
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- USUARIO DE PRUEBA
-- ============================================
-- Contraseña: admin123 (hasheada con bcrypt)
INSERT INTO usuarios (username, password, nombre, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@sistema.com');

-- ============================================
-- PRODUCTOS DE EJEMPLO
-- ============================================
INSERT INTO productos (nombre, descripcion, categoria, precio, stock, imagen) VALUES
('Laptop HP Pavilion', 'Laptop con procesador Intel i7, 16GB RAM', 'Electrónica', 899.99, 15, ''),
('Mouse Inalámbrico', 'Mouse ergonómico con batería recargable', 'Accesorios', 29.99, 50, ''),
('Teclado Mecánico', 'Teclado RGB con switches Cherry MX', 'Accesorios', 79.99, 8, ''),
('Monitor 27 pulgadas', 'Monitor 4K IPS con HDR', 'Electrónica', 349.99, 3, ''),
('Webcam HD', 'Cámara web 1080p con micrófono', 'Accesorios', 59.99, 0, '');