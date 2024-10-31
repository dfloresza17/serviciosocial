<?php
session_start();
include("bd.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM dependencia where id = $id";
    $resultado= mysqli_query($conn, $query);
 
        if (!$resultado) {
            die("Error");
        }

    $_SESSION['message'] = 'Dependencia eliminada';
    $_SESSION['message_type'] = 'danger';
    header('Location: dependencia.php');
}
?>