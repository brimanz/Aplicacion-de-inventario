<?php
/**
 * Clase Database - Maneja la conexión a MySQL usando PDO
 * Ubicación: config/database.php
 */

class Database {
    // Configuración de la base de datos
    private $host = "localhost";
    private $db_name = "database";
    private $username = "root";
    private $password = "";
    private $port = "400";
    private $conn;

    /**
     * Método para obtener la conexión a la base de datos
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Configurar PDO para lanzar excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configurar el modo de fetch por defecto
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Desactivar emulación de prepared statements para mayor seguridad
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $e) {
            // En producción, registrar el error en un log en lugar de mostrarlo
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
        }
        
        return $this->conn;
    }
}
?>