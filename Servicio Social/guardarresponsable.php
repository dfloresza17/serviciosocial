<?php 
session_start();
include("bd.php");

if (isset($_POST['guardar'])) { // Verifica si el formulario ha sido enviado
    $nombreResponsable = $_POST['NombreResponsable'];
    $cargo = $_POST['Cargo'];
    $area = $_POST['Area'];
    $telefono = $_POST['Telefono'];
    $dependencia = $_POST['dependencia']; // El ID de la dependencia seleccionada

    // Consulta SQL para insertar un nuevo responsable
    $query = "INSERT INTO responsables (NombreResponsable, Cargo, Area, Telefono, dependencia) 
              VALUES ('$nombreResponsable', '$cargo', '$area', '$telefono', '$dependencia')";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado) {
        die("Error al guardar el responsable.");
    }

    // Mensaje de éxito
    $_SESSION['message'] = 'Responsable guardado';
    $_SESSION['message_type'] = 'success';

    // Redirigir a la página de responsables
    header("location: Responsable.php");
}
?>
