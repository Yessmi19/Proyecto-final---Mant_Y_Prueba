<?php
session_start();
include("con_db.php");

$nombreUsuario = $_SESSION['nombre'];
$idCita = $_GET['id'];

// Verificar si la cita pertenece al usuario
$query = "SELECT * FROM citas WHERE id = '$idCita' AND nombre_paciente = '$nombreUsuario'";
$result = mysqli_query($conex, $query);

if (mysqli_num_rows($result) == 1) {
    // Eliminar la cita de la base de datos
    $deleteQuery = "DELETE FROM citas WHERE id = '$idCita'";
    if (mysqli_query($conex, $deleteQuery)) {
        // Redirigir a la página de historial o a donde desees después de cancelar la cita
        header("Location: Historial.php");
        exit();
    } else {
        echo "Error al cancelar la cita: " . mysqli_error($conex);
    }
} else {
    // Si no se encuentra la cita o no pertenece al usuario, redireccionar o mostrar un mensaje de error
    // Aquí deberías manejar el caso donde la cita no se encuentra o no pertenece al usuario
    header("Location: Historial.php");
    exit();
}
?>
