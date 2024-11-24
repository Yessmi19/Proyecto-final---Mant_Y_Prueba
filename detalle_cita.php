<?php
session_start();
include("con_db.php");

$nombreUsuario = $_SESSION['nombre'];
$idCita = $_GET['id'];

// Consulta SQL para obtener detalles de la cita seleccionada
$query = "SELECT citas.*, especialistas.nombre AS especialista
          FROM citas
          JOIN especialistas ON citas.especialista_id = especialistas.id
          WHERE citas.id = '$idCita' AND citas.nombre_paciente = '$nombreUsuario'";
$result = mysqli_query($conex, $query);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $idCita = $row['id'];
    $fecha = $row['fecha'];
    $hora = $row['hora'];
    $especialista = $row['especialista'];
    $motivo = $row['motivo']; // Asumiendo que 'motivo' es un campo en tu tabla 'citas'
} else {
    // Si no se encuentra la cita o no pertenece al usuario, redireccionar o mostrar un mensaje de error
    // Aquí deberías manejar el caso donde la cita no se encuentra o no pertenece al usuario
    header("Location: Historial.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .horizontal-divider {
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
            color: #333;
        }

        .btn {
            margin-right: 10px;
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
    <div class="container"     style="margin-top: 100px;">
        <h3>Detalles de la Cita</h3>
        <?php if (isset($idCita)) { ?>
        <p><strong>ID de Cita:</strong> <?php echo $idCita; ?></p>
        <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
        <p><strong>Hora:</strong> <?php echo $hora; ?></p>
        <p><strong>Especialista:</strong> <?php echo $especialista; ?></p>
        <p><strong>Motivo:</strong> <?php echo $motivo; ?></p>
        <?php } else { ?>
        <p>No se encontraron detalles de la cita.</p>
        <?php } ?>
    </div>
</body>
</html>






