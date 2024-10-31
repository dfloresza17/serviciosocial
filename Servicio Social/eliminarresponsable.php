<?php
include("bd.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM responsables where id = $id";
    $resultado= mysqli_query($conn, $query);
 
        if (!$resultado) {
            die("Error");
        }

    $_SESSION['message'] = 'Responsable eliminado';
    $_SESSION['message_type'] = 'danger';
    header('Location: responsable.php');
}
?>