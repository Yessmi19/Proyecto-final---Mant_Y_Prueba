<?php
session_start();
include("con_db.php");

$nombreUsuario = $_SESSION['nombre'];
$idCita = $_POST['idCita'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$motivo = $_POST['motivo'];

// Actualizar la cita en la base de datos
$query = "UPDATE citas SET fecha = '$fecha', hora = '$hora', motivo = '$motivo' WHERE id = '$idCita' AND nombre_paciente = '$nombreUsuario'";
$result = mysqli_query($conex, $query);

if ($result) {
    // Redirigir a la página de historial o a donde desees después de guardar los cambios
    header("Location: Historial.php");
    exit();
} else {
    // Manejar errores de forma más adecuada, por ejemplo, redirigir con un mensaje de error
    $_SESSION['error_message'] = "Error al actualizar la cita: " . mysqli_error($conex);
    header("Location: editar_cita.php?id=$idCita"); // Redirige de vuelta al formulario de edición con el ID de la cita
    exit();
}
?>
