<?php
session_start();
include("con_db.php");

$nombreUsuario = $_SESSION['nombre'];
$idCita = $_GET['id'];

// Obtener los datos actuales de la cita para prellenar el formulario de edición
$query = "SELECT * FROM citas WHERE id = '$idCita' AND nombre_paciente = '$nombreUsuario'";
$result = mysqli_query($conex, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    // Aquí obtienes los datos necesarios para prellenar el formulario de edición
    $fecha = $row['fecha'];
    $hora = $row['hora'];
    $motivo = isset($row['motivo']) ? $row['motivo'] : ''; // Verificar si 'motivo' está definido en la base de datos
} else {
    // Si no se encuentra la cita o no pertenece al usuario, redireccionar o mostrar un mensaje de error
    $_SESSION['error_message'] = "La cita no se encuentra o no pertenece al usuario.";
    header("Location: Historial.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .horizontal-divider {
            height: 1px;
            background-color: #ddd;
            margin: 10px 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 10%);
            margin-top: 100px;
        }
        .form-control {
            margin-bottom: 15px;
            color: #000000;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="Imagenes/Logoo.png" alt="Logo" style="width: 135px;">
        <div class="user-info">
            <img class="user-icon" src="Imagenes/IconUser.png" alt="Icono de usuario">
            <h6 class="user-name"><?php echo $nombreUsuario; ?></h6>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="container">
        <h3>Editar Cita</h3>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); // Limpiar el mensaje de error después de mostrarlo
        }
        ?>
        <form action="actualizar_cita.php" method="POST">
            <input type="hidden" name="idCita" value="<?php echo $idCita; ?>">
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input style="padding-left: 0px;width: 210px; text-align: center;" type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input style="padding-left: 0px;width: 210px; text-align: center;" type="time" class="form-control" id="hora" name="hora" value="<?php echo $hora; ?>" required>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" id="motivo" name="motivo" rows="3" required><?php echo $motivo; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>





