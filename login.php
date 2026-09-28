<?php
/**
 * Página de Login
 * Ubicación: login.php
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validación básica
    if (empty($username) || empty($password)) {
        $error = "Por favor, complete todos los campos";
    } else {
        $conn = getDatabase();
        
        if (login($username, $password, $conn)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Inventario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">📦</div>
            <h1>Sistema de <span>Inventario</span></h1>
            <p class="login-subtitle">Ingrese sus credenciales para acceder</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"> Usuario</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Ingrese su usuario"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">🔒 Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Ingrese su contraseña"
                        required
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Iniciar Sesión
                </button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gris); text-align: center;">
                <p style="color: var(--gris-oscuro); font-size: 0.9rem;">
                    <strong>Credenciales de prueba:</strong><br>
                    Usuario: <code style="background: var(--gris-claro); padding: 0.2rem 0.5rem; border-radius: 4px;">admin</code> | 
                    Contraseña: <code style="background: var(--gris-claro); padding: 0.2rem 0.5rem; border-radius: 4px;">admin123</code>
                </p>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>