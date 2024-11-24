<?php
session_start();
include("con_db.php");

if (isset($_POST['confirm-cita'])) {
    // Verificar que todos los campos del formulario estén presentes y no estén vacíos
    if (!empty($_POST['specialist-type']) && isset($_SESSION['nombre']) && !empty($_POST['confirm-date']) && !empty($_POST['confirm-time']) && !empty($_POST['confirm-reason'])) {
        
        // Obtener los datos del formulario de confirmación
        $fecha = mysqli_real_escape_string($conex, $_POST['confirm-date']);
        $hora = mysqli_real_escape_string($conex, $_POST['confirm-time']);
        $especialista_id = mysqli_real_escape_string($conex, $_POST['specialist-type']);
        $nombre_paciente = $_SESSION['nombre'];
        $motivo = mysqli_real_escape_string($conex, $_POST['confirm-reason']);

        // Consulta SQL para verificar la disponibilidad del especialista
        $check_availability = "SELECT * FROM citas WHERE fecha = '$fecha' AND hora = '$hora' AND especialista_id = '$especialista_id'";
        $result = mysqli_query($conex, $check_availability);

        // Verificar si ya existe una cita para el especialista en la misma fecha y hora
        if (mysqli_num_rows($result) > 0) {
            // Si hay un conflicto de cita, mostrar una alerta y redirigir de nuevo al formulario
            echo "<script>alert('El especialista no está disponible en la fecha y hora seleccionadas. Por favor, elige otro horario.'); window.location.href='Agendarcita.php';</script>";
        } else {
            // Consulta SQL para insertar la cita en la base de datos
            $cons = "INSERT INTO citas (fecha, hora, especialista_id, nombre_paciente, motivo) VALUES ('$fecha', '$hora', '$especialista_id', '$nombre_paciente', '$motivo')";
            
            // Ejecutar la consulta
            if (mysqli_query($conex, $cons)) {
                echo "<script>alert('Cita registrada correctamente.'); window.location.href='Historial.php';</script>";
            } else {
                echo "<script>alert('Error al registrar la cita: " . mysqli_error($conex) . "'); window.location.href='Agendarcita.php';</script>";
            }
        }
    } else {
        // Si hay campos vacíos en el formulario
        echo "<script>alert('Por favor completa todos los campos del formulario.'); window.location.href='Agendarcita.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Agendar cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>
<body>
<?php
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
        <div id="usernameDisplay" style="display: none;"></div>
    </div>
</div>
<div class="horizontal-divider"></div>
<div class="sidebar">
    <nav>
        <ul>
            <li><a href="Home.php"><img src="Imagenes/home+.png" alt="Icon 1"> Home</a></li>
            <li><a href="Agendarcita.php"><img src="Imagenes/calendario.png" alt="Icon 2"> Agendar cita</a></li>
            <li><a href="Historial.php"><img src="Imagenes/historial.png" alt="Icon 3"> Historial</a></li>
            <li><a href=""><img src="Imagenes/Servicios1.png" alt="Icon 4"> Servicios</a></li>
            <li><a href=""><img src="Imagenes/especialistas.png" alt="Icon 5"> Especialistas</a></li>
            <li><a href=""><img src="Imagenes/contacto.png" alt="Icon 6"> Contacto</a></li>
        </ul>
        <ul style="padding-top: 10px;">
            <li><a href=""><img src="Imagenes/confi.png" alt="Icon 7">Configuración</a></li>
            <li><a href="Login.php" id="logout-link"><img src="Imagenes/salida.png" alt="Icon 8"> Salir</a></li>
        </ul>
    </nav>
</div>
<div class="sidebar-right" style="padding-top: 75px">
    <h4 class="user-na">Agenda tu cita</h4>
</div>
<form id="appointment-form" method="POST">
    <div class="form-container" style="padding-top: 0px">
        <label for="specialist-type">Tipo de Especialista:</label>
        <select id="specialist-type" style="width: 319px;" name="specialist-type">
            <option value="general">Psicólogo individual</option>
            <option value="psiquiatra">Psiquiatra</option>
            <option value="psicologoFamiliar">Terapeutas familiar y de parejas</option>
            <option value="psicologoGrupal">Terapia grupal</option>
        </select>
        <div id="specialists-list" class="specialists-list">
            <!-- Especialistas generales -->
            <div class="specialist general" data-id="1">
                <img src="Imagenes/Especialista 1.jpg" alt="Dra. Elena">
                <h3>Dra. Elena Montenegro</h3>
                <p>La Dra. Montenegro es una psicóloga clínica con experiencia en terapia cognitivo-conductual, enfocada en ayudar a los clientes a identificar y cambiar patrones de pensamiento y comportamiento que causan malestar emocional. Tiene un enfoque práctico y basado en la evidencia para tratar trastornos de ansiedad, depresión, y problemas de autoestima.</p>
                <button type="button" class="select-button" data-name="Dra. Elena Montenegro">Seleccionar</button>
            </div>
            <div class="specialist general" data-id="2">
                <img src="Imagenes/Especialista 2.jpg" alt="Dr. Alejandro">
                <h3>Dr. Alejandro Vargas</h3>
                <p>El Dr. Alejandro Vargas es un psicólogo clínico especializado en terapia cognitivo-conductual y terapia dialéctico-conductual. Se enfoca en tratar trastornos de ansiedad, del estado de ánimo y problemas emocionales. Utiliza un enfoque basado en la evidencia para ayudar a los pacientes a cambiar patrones de pensamiento negativos y mejorar su calidad de vida.</p>
                <button type="button" class="select-button" data-name="Dr. Alejandro Vargas">Seleccionar</button>
            </div>
            <div class="specialist general" data-id="3">
                <img src="Imagenes/Especialista 3.jpg" alt="Dra. Carolina">
                <h3>Dra. Carolina Ríos</h3>
                <p>La Dra. Carolina Ríos es una psicóloga clínica con experiencia en terapia cognitivo-conductual y terapia de aceptación y compromiso. Se especializa en el tratamiento de ansiedad, depresión y estrés. Su enfoque es práctico y basado en la evidencia, ayudando a los clientes a desarrollar habilidades para mejorar su bienestar emocional y problemas de autoestima.</p>
                <button type="button" class="select-button" data-name="Dra. Carolina Ríos">Seleccionar</button>
            </div>
            <!-- Psicólogos -->
            <div class="specialist psiquiatra" data-id="4">
                <img src="Imagenes/Especialista 4.jpg" alt="Dr. Carlos">
                <h3>Dr. Carlos Méndez</h3>
                <p>El Dr. Carlos Méndez es un psicólogo clínico especializado en terapia cognitivo-conductual y terapia dialéctico-conductual. Trabaja con pacientes para tratar trastornos de ansiedad y del estado de ánimo. Utiliza un enfoque práctico y basado en la evidencia para promover cambios positivos y duraderos.</p>
                <button type="button" class="select-button" data-name="Dr. Carlos Méndez">Seleccionar</button>
            </div>
            <div class="specialist psiquiatra" data-id="5">
                <img src="Imagenes/Especialista 5.jpg" alt="Dra. Laura">
                <h3>Dra. Laura Gómez</h3>
                <p>La Dra. Laura Gómez es una psicóloga clínica con experiencia en terapia cognitivo-conductual, enfocada en ayudar a los clientes a gestionar la ansiedad, la depresión y el estrés. Su enfoque basado en la evidencia permite a los pacientes desarrollar habilidades para mejorar su bienestar emocional.</p>
                <button type="button" class="select-button" data-name="Dra. Laura Gómez">Seleccionar</button>
            </div>
            <!-- Psicólogos familiares -->
            <div class="specialist psicologoFamiliar" data-id="6">
                <img src="Imagenes/Especialista 6.jpg" alt="Dra. Ana">
                <h3>Dra. Ana Martínez</h3>
                <p>La Dra. Ana Martínez es una psicóloga clínica especializada en terapia cognitivo-conductual y terapia dialéctico-conductual. Trabaja con pacientes para tratar trastornos de ansiedad y del estado de ánimo. Utiliza un enfoque práctico y basado en la evidencia para promover cambios positivos y duraderos.</p>
                <button type="button" class="select-button" data-name="Dra. Ana Martínez">Seleccionar</button>
            </div>
            <div class="specialist psicologoFamiliar" data-id="7">
                <img src="Imagenes/Especialista 7.jpg" alt="Dra. Lucia">
                <h3>Dra. María Sánchez</h3>
                <p>Dra. María Sánchez es una psicóloga clínica con experiencia en terapia cognitivo-conductual, enfocada en ayudar a los clientes a gestionar la ansiedad, la depresión y el estrés. Su enfoque basado en la evidencia permite a los pacientes desarrollar habilidades para mejorar su bienestar emocional. Enfocada en mejor tú salud</p>
                <button type="button" class="select-button" data-name="Dra. Lucia Pérez">Seleccionar</button>
            </div>
            <!-- Psicólogos grupales -->
            <div class="specialist psicologoGrupal" data-id="8">
                <img src="Imagenes/Especialista 8.jpg" alt="Dr. Juan">
                <h3>Dra. Clara Ruiz</h3>
                <p>Dra. Clara Ruiz es un psicólogo clínico especializado en terapia cognitivo-conductual y terapia dialéctico-conductual. Trabaja con pacientes para tratar trastornos de ansiedad y del estado de ánimo. Utiliza un enfoque práctico y basado en la evidencia para promover cambios positivos y duraderos.</p>
                <button type="button" class="select-button" data-name="Dr. Juan Rodríguez">Seleccionar</button>
            </div>
            <div class="specialist psicologoGrupal" data-id="9">
                <img src="Imagenes/Especialista 9.jpg" alt="Dra. Maria">
                <h3>Dr. Andrés Castro</h3>
                <p>Dr. Andrés Castro es una psicóloga clínica con experiencia en terapia cognitivo-conductual, enfocada en ayudar a los clientes a gestionar la ansiedad, la depresión y el estrés. Su enfoque basado en la evidencia permite a los pacientes desarrollar habilidades para mejorar su bienestar emocional. Trabaja arduameante</p>
                <button type="button" class="select-button" data-name="Dra. Maria López">Seleccionar</button>
            </div>
        </div>
    </div>
    <div class="appointment-details">
        <label style="padding-left: 0px;width: 210px;margin-left: 250p;margin-left: 250px;" for="appointment-date">Fecha:</label>
        <input style="padding-left: 0px;width: 210px;margin-left: 250p;margin-left: 250px; text-align: center;" type="date" id="appointment-date" name="appointment-date" class="form-control">
    </div>
    <div class="appointment-details">
        <label style="padding-left: 0px;width: 210px;margin-left: 250p;margin-left: 250px;padding-bottom: 10px;" for="appointment-time">Hora:</label>
        <input style="padding-left: 0px;width: 210px;margin-left: 250p;margin-left: 250px;text-align: center;" type="time" id="appointment-time" name="appointment-time" class="form-control">
    </div>
    <div class="appointment-details">
        <label style="padding-left: 0px;width: 210px;margin-left: 250p;margin-left: 250px;"  for="appointment-reason" style="width: 500px; height: 0px;">Motivo de la consulta:</label>
        <textarea style="padding-left: 20px;width: 1000px;margin-left: 250p;margin-left: 250px; padding-bottom: 30px;;" id="appointment-reason" name="appointment-reason"></textarea>
    </div>
    <div class="submit-btn">
        <button type="button" class="confirm-button">Siguiente</button>
    </div>
  
    <!-- Modal de confirmación -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirmar cita</h2>
            <p>Especialista: <span id="confirm-specialist"></span></p>
            <p>Fecha: <span id="confirm-date"></span></p>
            <p>Hora: <span id="confirm-time"></span></p>
            <p>Motivo: <span id="confirm-reason"></span></p>
            <form id="confirm-form" method="POST">
                <input type="hidden" id="hidden-specialist-type" name="specialist-type">
                <input type="hidden" id="hidden-date" name="confirm-date">
                <input type="hidden" id="hidden-time" name="confirm-time">
                <input type="hidden" id="hidden-reason" name="confirm-reason">
                <div class="submit-btn">
                    <button type="submit" name="confirm-cita">Confirmar</button>
                </div>
                <div class="submit-btn">
                    <button type="submit" id="cancel-button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // Mostrar nombre de usuario en el encabezado
    var nombreUsuario = <?php echo json_encode($nombreUsuario); ?>;
    $('#usernameDisplay').text(nombreUsuario).show();

    // Filtrar especialistas según el tipo seleccionado
    $('#specialist-type').change(function() {
        var selectedType = $(this).val();
        $('.specialist').hide();
        $('.' + selectedType).show();
    }).trigger('change'); // Forzar el cambio al cargar la página

    // Manejar selección de especialista
    $('.select-button').click(function() {
        var specialistName = $(this).data('name');
        var specialistId = $(this).closest('.specialist').data('id');
        $('#confirm-specialist').text(specialistName);
        $('#hidden-specialist-type').val(specialistId);
    });

    // Mostrar modal de confirmación
    $('.confirm-button').click(function() {
        var selectedSpecialist = $('#confirm-specialist').text();
        var selectedDate = $('#appointment-date').val();
        var selectedTime = $('#appointment-time').val();
        var selectedReason = $('#appointment-reason').val();

        // Verificar que todos los campos estén completos
        if (selectedSpecialist && selectedDate && selectedTime && selectedReason) {
            $('#confirm-date').text(selectedDate);
            $('#confirm-time').text(selectedTime);
            $('#confirm-reason').text(selectedReason);
            $('#hidden-date').val(selectedDate);
            $('#hidden-time').val(selectedTime);
            $('#hidden-reason').val(selectedReason);
            $('#confirmationModal').show();
        } else {
            alert('Por favor, completa todos los campos antes de confirmar.');
        }
    });

    // Cerrar modal de confirmación
    $('.close').click(function() {
        $('#confirmationModal').hide();
    });
});

       document.addEventListener('DOMContentLoaded', function () {
   // Obtener la URL actual
   const currentUrl = window.location.pathname.split('/').pop();
   // Seleccionar todos los enlaces de la barra lateral
   const menuItems = document.querySelectorAll('.sidebar nav ul li a');
   menuItems.forEach(link => {
       // Comprobar si el enlace coincide con la URL actual
       if (link.getAttribute('href') === currentUrl) {
           // Añadir la clase 'active' al elemento li padre del enlace
           link.parentElement.classList.add('active');
       }
   });
   const selectButtons = document.querySelectorAll('.select-button');
   let selectedSpecialist = '';
   selectButtons.forEach(button => {
       button.addEventListener('click', function () {
           selectButtons.forEach(btn => {
               btn.classList.remove('selected');
               btn.disabled = false;
           });
           this.classList.add('selected');
           this.disabled = true; // Desactiva el botón seleccionado
           selectedSpecialist = this.getAttribute('data-name');
       });
   });
   document.getElementById('confirm-button').addEventListener('click', function () {
       alert('Cita confirmada.');
       document.getElementById('confirmation-modal').style.display = 'none';
       window.location.href = 'Historial.html'; // Redirigir a la página de historial de citas
   });
   document.getElementById('cancel-button').addEventListener('click', function () {
       document.getElementById('confirmation-modal').style.display = 'none';
   });
   document.querySelector('.close-button').addEventListener('click', function () {
       document.getElementById('confirmation-modal').style.display = 'none';
   });
   document.getElementById('logout-link').addEventListener('click', function (event) {
       event.preventDefault(); // Evita que el enlace navegue inmediatamente
       var confirmation = confirm('¿Estás seguro de que deseas salir?');
       if (confirmation) {
           window.location.href = this.href; // Navega a la página de logout si el usuario confirma
       }
   });
});
</script>
</body>
</html>

