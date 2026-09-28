<?php
/**
 * Sistema de Autenticación
 * Ubicación: includes/auth.php
 * Maneja login, logout y verificación de sesiones
 */

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está logueado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirige al login si el usuario no está autenticado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Función de login - Valida credenciales del usuario
 * @param string $username
 * @param string $password
 * @param PDO $conn
 * @return bool
 */
function login($username, $password, $conn) {
    // Preparar statement para prevenir SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password, nombre, email FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Guardar datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['login_time'] = time();
        
        return true;
    }
    
    return false;
}

/**
 * Función de logout - Destruye la sesión
 */
function logout() {
    // Limpiar todas las variables de sesión
    $_SESSION = array();
    
    // Destruir la cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir la sesión
    session_destroy();
    
    // Redirigir al login
    header("Location: login.php");
    exit();
}

/**
 * Obtener el usuario actual desde la sesión
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'nombre' => $_SESSION['nombre'],
        'email' => $_SESSION['email']
    ];
}
?>