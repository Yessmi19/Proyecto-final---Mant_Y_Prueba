<?php
include("con_db.php"); // Incluir el archivo de conexión a la base de datos

if (isset($_POST['registers'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['cedula']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
        $cedula = mysqli_real_escape_string($conex, $_POST['cedula']);
        $correo = mysqli_real_escape_string($conex, $_POST['email']);
        $contraseña = mysqli_real_escape_string($conex, $_POST['password']);

        // Verificar si el correo ya está registrado
        $checkEmail = "SELECT * FROM datos WHERE correo='$correo'";
        $result = mysqli_query($conex, $checkEmail);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('¡El correo ya está registrado!'); window.location.href='Registro.php';</script>";
        } else {
            // Validar el formato de la cédula
            if (!preg_match('/^\d{1,2}-\d{3,4}-\d{3,4}$/', $cedula)) {
                echo "<script>alert('¡El formato de la cédula es incorrecto! Debe tener el carácter (-) en el formato correcto.'); window.location.href='Registro.php';</script>";
                exit();
            }

            // Preparar la consulta SQL para insertar el nuevo usuario
            $consulta = "INSERT INTO datos (nombre_completo, cedula, correo, contraseña) VALUES ('$nombre', '$cedula', '$correo', '$contraseña')";
            $resultado = mysqli_query($conex, $consulta);

            if ($resultado) {
                // Registro exitoso, establecer el nombre de usuario en la sesión si es necesario
                session_start();
                $_SESSION['nombre'] = $nombre;
                header("Location: Login.php?success=registered");
                exit(); // Detener la ejecución del script después de la redirección
            } else {
                header("Location: Registro.php?error=registration_failed");
                exit(); // Detener la ejecución del script después de la redirección
            }
        }
    } else {
        header("Location: Registro.php?error=empty_fields");
        exit(); // Detener la ejecución del script después de la redirección
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
            <p id="error-message-cedula" style="color: red; display: none;">La cédula registrada debe tener el carácter (-) en el formato correcto</p>         
            <label for="username">Correo electrónico o nombre de usuario</label>
            <input type="text" id="email" placeholder="ejemplo@gmail.com" name="email" required>    
            <label for="password">Contraseña</label>
            <div class="password-container">
                <input type="password" id="passwordR" name="password" required 
                pattern="(?=.*[A-Z])(?=.*[-*_!@#$%^&*()_+{}\[\]:\";,.]).{8,}" 
                title="La contraseña debe contener al menos una letra mayúscula, y uno de los siguientes caracteres: -, *, _, !, @, #, $, %, ^, &, *, (, ), _, +, {, }, [, ], :, \", ;, ,, .">
                <p id="error-message-password" style="color: red; display: none;">La contraseña debe contener al menos una letra mayúscula, y uno de los siguientes caracteres: -, *, _, !, @, #, $, %, ^, &, *, (, ), _, +, {, }, [, ], :, ", ;, ,, .</p>
            </div>
            <button type="submit" name="registers" class="login-button">Registrar</button>
            <p class="register-text">¿Ya estás registrado? <a href="Login.php" class="register-link">Iniciar sesión</a></p>
        </form> 
    </div>
    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario hasta que la validación sea correcta

            const cedulaInput = document.getElementById('cedula').value;
            const errorMessageCedula = document.getElementById('error-message-cedula');
            const errorMessagePassword = document.getElementById('error-message-password');

            // Expresión regular ajustada para validar formatos más comunes de la cédula
            const cedulaRegex = /^\d{1,2}-\d{3,4}-\d{3,4}$/;

            if (!cedulaRegex.test(cedulaInput)) {
                errorMessageCedula.style.display = 'block';
                return; // Detener la validación si la cédula no cumple el formato
            } else {
                errorMessageCedula.style.display = 'none';
            }

            // Validación de contraseña en JavaScript
            const passwordInput = document.getElementById('passwordR').value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[*_!@#$%^&*()_+{}\[\]:\";,.]).{8,}$/;

            if (!passwordRegex.test(passwordInput)) {
                errorMessagePassword.style.display = 'block';
                return; // Detener el envío del formulario si la contraseña no cumple con los requisitos
            } else {
                errorMessagePassword.style.display = 'none';
            }

            // Si todo está validado correctamente, enviar el formulario
            event.target.submit();
        });
    </script>
</body>
</html>



