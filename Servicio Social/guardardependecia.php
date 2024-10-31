<?php 
session_start();
include ("bd.php");
if (isset($_POST['guardar'])){ // Verifica si el formulario ha sido enviado
    $nombre = $_POST['nombre'];
    $siglas = $_POST['siglas'];
    $direccion = $_POST['direccion'];
    $titular = $_POST['titular'];
    $telefono = $_POST['telefono'];
    $enlace = $_POST['enlace'];
    $telefonoEnlace = $_POST['telefonoEnlace'];


    $query = "INSERT INTO dependencia(Nombre,Siglas,Direccion,Titular,Telefono,Enlace,TelefonoEnlace) 
    VALUES('$nombre', '$siglas', '$direccion', '$titular', '$telefono', '$enlace', '$telefonoEnlace')";
    $resultado = mysqli_query($conn, $query);
    if (!$resultado){
        die("Error");
    }

    $_SESSION['message'] ='Dependencia guardada';
    $_SESSION['message_type'] = 'success';

    header("location: dependencia.php");
}

?>