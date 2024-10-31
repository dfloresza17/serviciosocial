<?php
session_start();
include("bd.php");

// Verifica si se ha pasado el ID de la tarea a eliminar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Escapa el ID para prevenir inyecciones SQL
    $id = mysqli_real_escape_string($conn, $id);

    // Consulta para eliminar la tarea
    $query = "DELETE FROM tareas WHERE id = $id";
    $resultado = mysqli_query($conn, $query);

    // Verifica si la consulta fue exitosa
    if (!$resultado) {
        die("Error al eliminar la tarea: " . mysqli_error($conn));
    }

    // Mensaje de éxito
    $_SESSION['message'] = 'Tarea eliminada';
    $_SESSION['message_type'] = 'danger';
    
    // Redirige a la página de tareas
    header('Location: tarea.php');
    exit(); // Asegúrate de salir después de redirigir
}
?>
