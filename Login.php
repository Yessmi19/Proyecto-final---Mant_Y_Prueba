<?php
session_start(); // Iniciar la sesión
include("con_db.php"); // Asumiendo que este archivo contiene la conexión a la base de datos

if (isset($_POST['logins'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = mysqli_real_escape_string($conex, $_POST['email']);
        $password = mysqli_real_escape_string($conex, $_POST['password']);

        $correo = trim($email);
        $contraseña = trim($password);

        // Verificar si el usuario existe por correo o nombre
        $query = "SELECT * FROM datos WHERE (correo='$correo' OR nombre_completo='$correo') AND contraseña='$contraseña'";
        $result = mysqli_query($conex, $query);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);
            $_SESSION['nombre'] = $usuario['nombre_completo']; // Almacenar el nombre en la sesión
            echo "<script>window.location.href='Home.php';</script>";
        } else {
            // Contraseña incorrecta o correo/nombre no registrado
            echo "<script>alert('¡Credenciales incorrectas!'); window.history.back();</script>";
        }
    } else {
        // Campos vacíos
        echo "<script>alert('¡Por favor complete los campos!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="headerprin">
        <img src="Imagenes/Logoo.png" alt="Logo">
    </div>
    <div class="login-container">
        <h1>Bienvenido a N<span class="highlight">Day</span></h1>
        <p class="pcontainer">Complete sus datos para iniciar sesión en su cuenta</p>
        <form method="POST">
            <label for="username">Correo electrónico o nombre de usuario</label>
            <input type="text" id="username" name="email" required>
            <label for="password">Contraseña <img src="Imagenes/obligatorio.png" alt="required" class="obligatorio-icon"></label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
            </div>          
            <button type="submit" name="logins" class="login-button">Iniciar</button>        
            <p class="register-text">¿No tiene una cuenta? <a href="Registro.php" class="register-link" style="text-decoration: none;">Registrar</a></p>
        </form>
    </div>
</body>
</html>