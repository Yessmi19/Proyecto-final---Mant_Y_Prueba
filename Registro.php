<?php
include("con_db.php"); // Incluir el archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['nombre']) && !empty($_POST['cedula']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
        $cedula = mysqli_real_escape_string($conex, $_POST['cedula']);
        $correo = mysqli_real_escape_string($conex, $_POST['email']);
        $contraseña = mysqli_real_escape_string($conex, $_POST['password']);

        // Validar el formato del correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('¡El formato del correo electrónico no es válido!'); window.location.href='Registro.php';</script>";
            exit();
        }

        // Verificar si el correo ya está registrado
        $checkEmail = "SELECT * FROM datos WHERE correo='$correo'";
        $result = mysqli_query($conex, $checkEmail);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('¡El correo ya está registrado!'); window.location.href='Registro.php';</script>";
        } else {
            // Validar el formato de la cédula
            if (!preg_match('/^\d{1,2}-\d{3,4}-\d{3,4}$/', $cedula)) {
                echo "<script>alert('¡El formato de la cédula es incorrecto!'); window.location.href='Registro.php';</script>";
                exit();
            }

            // Preparar la consulta SQL para insertar el nuevo usuario
            $consulta = "INSERT INTO datos (nombre_completo, cedula, correo, contraseña) VALUES ('$nombre', '$cedula', '$correo', '$contraseña')";
            $resultado = mysqli_query($conex, $consulta);

            if ($resultado) {
                // Registro exitoso
                session_start();
                $_SESSION['nombre'] = $nombre;
                echo "<script>alert('¡Te has registrado correctamente!'); window.location.href='Login.php';</script>";
            } else {
                echo "<script>alert('¡Ups! Ha ocurrido un error al registrar.'); window.location.href='Registro.php';</script>";
            }
        }
    } else {
        echo "<script>alert('¡Por favor complete todos los campos!'); window.location.href='Registro.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Registro</title>
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
        <p class="pcontainer">Complete sus datos para registrar su cuenta</p>
        <form id="registroForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nombre">Nombre y apellido</label>
            <input type="text" id="nombre" name="nombre" required> 
            <label for="cedula">Cédula</label>
            <input type="text" id="cedula" name="cedula" required>
            <p id="error-message" style="color: red; display: none;">La cédula registrada debe tener el carácter (-) en el formato correcto</p>         
            <label for="email">Correo electrónico</label>
            <input type="text" id="email" placeholder="ejemplo@gmail.com" name="email" required>    
            <label for="password">Contraseña</label>
            <div class="password-container">
                <input type="password" id="passwordR" name="password" required 
                pattern="(?=.*[A-Z])(?=.*[-*_])(?=.*[!@#$%^&*()_+{}\[\]:\";,.]).{1,10}" 
                title="Máximo 10 caracteres, al menos una mayúscula, un guion (-), un asterisco (*) o un guion bajo (_), y un carácter especial">
            </div>
            <button type="submit" name="registers" class="login-button">Registrar</button>
            <p class="register-text">¿Ya estás registrado? <a href="Login.php" class="register-link">Iniciar sesión</a></p>
        </form> 
    </div>
    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario hasta que la validación sea correcta

            const cedulaInput = document.getElementById('cedula').value;
            const errorMessage = document.getElementById('error-message');

            // Validar cédula
            const cedulaRegex = /^\d{1,2}-\d{3,4}-\d{3,4}$/;
            if (!cedulaRegex.test(cedulaInput)) {
                errorMessage.style.display = 'block';
                return; // Detener la validación si la cédula no cumple el formato
            } else {
                errorMessage.style.display = 'none';
            }

            // Validar correo
            const emailInput = document.getElementById('email').value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(emailInput)) {
                alert('Por favor, ingresa un correo electrónico válido.');
                return; // Detener el envío del formulario si el correo no es válido
            }

            // Validar contraseña
            const passwordInput = document.getElementById('passwordR').value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[-*_])(?=.*[!@#$%^&*()_+{}\[\]:\";,.]).{1,10}$/;
            if (!passwordRegex.test(passwordInput)) {
                alert('La contraseña no cumple con los requisitos de seguridad.');
                return; // Detener el envío del formulario si la contraseña no es válida
            }

            // Si todo está validado correctamente, enviar el formulario
            event.target.submit();
        });
    </script>
</body>
</html>
