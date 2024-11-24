<?php
session_start();
include("con_db.php");

$nombreUsuario = $_SESSION['nombre'];

// Consulta SQL para obtener el historial de citas con el nombre del especialista
$query = "SELECT citas.id, citas.fecha, citas.hora, especialistas.nombre AS especialista
          FROM citas
          JOIN especialistas ON citas.especialista_id = especialistas.id
          WHERE citas.nombre_paciente = '$nombreUsuario'";
$result = mysqli_query($conex, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Historial de cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="height: 0px;">
    <div class="header">
        <img src="Imagenes/Logoo.png" alt="Logo" style="width: 135px;">
        <div class="user-info">
            <img class="user-icon" src="Imagenes/IconUser.png" alt="Icono de usuario">
            <h6 class="user-name"><?php echo $nombreUsuario; ?></h6>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="sidebar">
        <nav>
            <ul>
                <li><a href="Home.php"><img src="Imagenes/home+.png" alt="Icon 1"> Home</a></li>
                <li><a href="Agendarcita.php"><img src="Imagenes/calendario.png" alt="Icon 2"> Agendar Cita</a></li>
                <li><a href="Historial.php" class="active"><img src="Imagenes/historial.png" alt="Icon 3"> Historial de citas</a></li>
                <li><a href=""><img src="Imagenes/Servicios1.png" alt="Icon 4"> Servicios</a></li>
                <li><a href=""><img src="Imagenes/especialistas.png" alt="Icon 5"> Especialistas</a></li>
                <li><a href=""><img src="Imagenes/contacto.png" alt="Icon 6"> Contacto</a></li>
            </ul>
            <ul style="padding-top: 10px;">
                <li><a href=""><img src="Imagenes/confi.png" alt="Icon 1">Configuración</a></li>
                <li><a href="Login.php" id="logout-link"><img src="Imagenes/salida.png" alt="Icon 8">Salida</a></li>
            </ul>
        </nav>
    </div>
    <div class="sidebar-right">
        <h4 class="user-na">Historial de citas</h4>
    </div>
    <div class="filter-form" style="margin-left: 350px; width: 700px; margin-bottom: 20px;">
        <!-- Formulario de búsqueda y filtro -->
        <form id="filterForm" method="GET">
            <input style="margin-bottom: 10px;" type="text" name="search" placeholder="Buscar por fecha, hora, especialista" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <select name="especialista">
                <option value="">Todos los especialistas</option>
                <?php
                $especialistasQuery = "SELECT id, nombre FROM especialistas";
                $especialistasResult = mysqli_query($conex, $especialistasQuery);
                while ($especialista = mysqli_fetch_assoc($especialistasResult)) {
                    $selected = (isset($_GET['especialista']) && $_GET['especialista'] == $especialista['id']) ? 'selected' : '';
                    echo "<option value='{$especialista['id']}' $selected>{$especialista['nombre']}</option>";
                }
                ?>
            </select>
            <button type="submit" id="filterFormBtn">Filtrar</button>
        </form>
    </div>
    <!-- Tabla de citas -->
    <div class="table-container" style="margin-left: 350px; margin-bottom: 30px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Especialista</th>
                    <th>Ver Detalles</th>
                    <th>Editar</th>
                    <th>Cancelar</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $especialistaFilter = isset($_GET['especialista']) ? $_GET['especialista'] : '';

                // Modificar la consulta SQL según los filtros
                $query = "SELECT citas.id, citas.fecha, citas.hora, especialistas.nombre AS especialista
                          FROM citas
                          JOIN especialistas ON citas.especialista_id = especialistas.id
                          WHERE citas.nombre_paciente = '$nombreUsuario'";

                if ($search) {
                    $query .= " AND (citas.fecha LIKE '%$search%' OR citas.hora LIKE '%$search%' OR especialistas.nombre LIKE '%$search%')";
                }

                if ($especialistaFilter) {
                    $query .= " AND citas.especialista_id = '$especialistaFilter'";
                }

                $result = mysqli_query($conex, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['fecha'] . "</td>";
                        echo "<td>" . $row['hora'] . "</td>";
                        echo "<td>" . $row['especialista'] . "</td>";
                        echo "<td><a href='detalle_cita.php?id=" . $row['id'] . "'>Ver Detalles</a></td>";
                        echo "<td><a href='editar_cita.php?id=" . $row['id'] . "'>Editar</a></td>";
                        echo "<td><a href='cancelar_cita.php?id=" . $row['id'] . "'>Cancelar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No tienes citas programadas.</td></tr>";
                    echo "<tr><td colspan='7'><a href='Home.php'>Regresar a la pantalla principal</a> o <a href='Agendarcita.php'>Programar una nueva cita</a></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault(); // Evita que el enlace navegue inmediatamente
            var confirmation = confirm('¿Estás seguro de que deseas salir?');
            if (confirmation) {
                window.location.href = this.href; // Navega a la página de logout si el usuario confirma
            }
        });
    </script>
</body>
</html>



