<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['nombre'])) {
        header("Location: Login.php"); // Redirigir al login si no hay sesión activa
        exit();
    }
    $nombreUsuario = $_SESSION['nombre'];
    ?>
    <div class="header">
        <img src="Imagenes/Logoo.png" alt="Logo" style="width: 135px;">
        <div class="user-info">
            <img class="user-icon" src="Imagenes/IconUser.png" alt="Icono de usuario">
            <h6 class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></h6>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="sidebar">
        <nav>
            <ul>
                <li><a href="Home.php"><img src="Imagenes/home+.png" alt="Icon 1"> Home</a></li>
                <li><a href="Agendarcita.php"><img src="Imagenes/calendario.png" alt="Icon 2"> Agendar Cita</a></li>
                <li><a href="Historial.php"><img src="Imagenes/historial.png" alt="Icon 3"> Historial de citas</a></li>
                <li><a href=""><img src="Imagenes/Servicios1.png" alt="Icon 4"> Servicios</a></li>
                <li><a href=""><img src="Imagenes/especialistas.png" alt="Icon 5"> Especialistas</a></li>
                <li><a href=""><img src="Imagenes/contacto.png" alt="Icon 6"> Contacto</a></li>
            </ul>
            <ul style="padding-top: 10px;">
                <li><a href=""><img src="Imagenes/confi.png" alt="Icon 7">Configuración</a></li>
                <li><a href="Login.php" id="logout-link"><img src="Imagenes/salida.png" alt="Icon 8">Salida</a></li>
            </ul>
        </nav>
    </div>
    <div class="sidebar-right">
        <h4 class="user-na">Buenos días,</h4>
        <span class="user-na"><?php echo htmlspecialchars($nombreUsuario); ?></span>
        <div class="greeting">
            <div class="text-container">
                <p class="bienvenido">¡Bienvenido al Consultorio Éxito Real! Somos una clínica dedicada a proporcionar una amplia gama de servicios de atención psicológica y psiquiátrica. Nuestro enfoque integral y personalizado garantiza que cada cliente reciba el apoyo necesario para mejorar su bienestar mental y emocional. Con un equipo altamente capacitado y comprometido, ofrecemos un entorno seguro y acogedor donde los pacientes pueden buscar ayuda profesional y trabajar hacia resultados positivos y duraderos.</p>
           </div>
           <img class="imag-bienvenido" src="Imagenes/imageHome.jpg" alt="Imagen de bienvenida">
       </div>
   </div>
   <script>
       document.getElementById('logout-link').addEventListener('click', function(event) {
           event.preventDefault(); // Evita que el enlace navegue inmediatamente
           var confirmation = confirm('¿Estás seguro de que deseas salir?');
           if (confirmation) {
               window.location.href = this.href; // Navega a la página de logout si el usuario confirma
           }
       });
       document.addEventListener('DOMContentLoaded', function () {
           // Obtener la URL actual
           const currentUrl = window.location.pathname.split('/').pop();
           // Seleccionar todos los enlaces de la barra lateral
           const menuItems = document.querySelectorAll('.sidebar nav ul li a');
           menuItems.forEach(link => {
               // Comprobar si el enlace coincide con la URL actual
               if (link.getAttribute('href') === currentUrl) {
                   // Añadir la clase 'active' al elemento padre del enlace coincidente
                   link.parentNode.classList.add('active');
               }
               // Añadir evento click a cada enlace
               link.addEventListener('click', function (event) {
                   // Remover la clase 'active' de todos los elementos del menú
                   menuItems.forEach(link => link.parentNode.classList.remove('active'));
                   // Añadir la clase 'active' al elemento padre del enlace clicado
                   this.parentNode.classList.add('active');
               });
           });
       });
   </script>
</body>
</html>
