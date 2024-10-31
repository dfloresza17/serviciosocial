<?php
include("bd.php");

$sql = "SELECT id, Siglas FROM dependencia";
$resultado = mysqli_query($conn, $sql);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM responsables WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $nombre = $row['NombreResponsable'];
        $cargo = $row['Cargo'];
        $area = $row['Area'];
        $telefono = $row['Telefono'];
        $dependencia_id = $row['dependencia'];
    }
}

if(isset($_POST['actualizar'])){
    // Capturar los datos enviados por el formulario
    $nombre = $_POST['NombreResponsable'];
    $cargo = $_POST['Cargo'];
    $area = $_POST['Area'];
    $telefono = $_POST['Telefono'];
    $dependencia_id = $_POST['dependencia'];

    // Actualizar la base de datos
    $query = "UPDATE responsables SET NombreResponsable = '$nombre', Cargo = '$cargo', Area = '$area', Telefono = '$telefono', dependencia = $dependencia_id WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['message'] ='Responsable actualizado';
        $_SESSION['message_type'] = 'warning';
    } else {
        $_SESSION['message'] ='Error al actualizar el responsable';
        $_SESSION['message_type'] = 'danger';
    }

    header("location: responsable.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/2b23531528.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Editar Responsable</title>
</head>

<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Editar Responsable</h1>
        </center>
    </header>
    <div class="container">
        <div class="col-md-3 mx-auto">
            <div class="form-container">
                <form class="form" action="editarresponsable.php?id=<?php echo $id; ?>" method="POST">
                    <label>
                        <input required="" placeholder="" type="text" class="input" name="NombreResponsable" value="<?php echo $nombre; ?>">
                        <span>Nombre Responsable</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="Cargo" value="<?php echo $cargo; ?>">
                        <span>Cargo</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="Area" value="<?php echo $area; ?>">
                        <span>Area</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="\d{10}" name="Telefono" value="<?php echo $telefono; ?>">
                        <span>Telefono</span>
                    </label>

                    <label for="custom-select">
                        <select id="custom-select" class="inputselect" name="dependencia">
                            <option value="0">Seleccione la dependencia</option>
                            <?php
                            if ($resultado->num_rows > 0) {
                                // Generar las opciones del select y marcar la seleccionada
                                while($row = $resultado->fetch_assoc()) {
                                    $selected = ($row['id'] == $dependencia_id) ? 'selected' : '';
                                    echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['Siglas'].'</option>';
                                }
                            } else {
                                echo '<option value="0">No hay dependencias disponibles</option>';
                            }
                            ?>
                        </select>
                    </label>
                    <button class="fancy" type="submit" name="actualizar">
                        <span class="top-key"></span>
                        <span class="text">Guardar</span>
                        <span class="bottom-key-1"></span>
                        <span class="bottom-key-2"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
