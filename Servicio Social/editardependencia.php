<?php

include("bd.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM dependencia WHERE id = $id";
    $resultado = mysqli_query($conn, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_array($resultado);
        //crea las demas vareable
        $nombre = $row['Nombre'];
        $siglas = $row['Siglas'];
        $direccion = $row['Direccion'];
        $titular = $row['Titular'];
        $telefono = $row['Telefono'];
        $enlace = $row['Enlace'];
        $telefonoEnlace = $row['TelefonoEnlace'];
    }
}

//header("Location: Dependencia.php");
if(isset($_POST['actualizar'])){
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $siglas = $_POST['siglas'];
    $direccion = $_POST['direccion'];
    $titular = $_POST['titular'];
    $telefono = $_POST['telefono'];
    $enlace = $_POST['enlace'];
    $telefonoEnlace = $_POST['telefonoEnlace'];

    //IMPRIME laas vareables
    /*echo "Nombre: ".$nombre."<br>";
    echo "Siglas: ".$siglas."<br>";
    echo "Dirección: ".$direccion."<br>";
    echo "Titular: ".$titular."<br>";
    echo "Teléfono: ".$telefono."<br>";
    echo "Enlace: ".$enlace."<br>";
    echo "Telefono enlace: ".$telefonoEnlace."<br>"; */

    $query = "UPDATE dependencia SET Nombre = '$nombre', Siglas = '$siglas', Direccion = '$direccion', Titular = '$titular',
     Telefono = '$telefono', Enlace = '$enlace', TelefonoEnlace = '$telefonoEnlace' WHERE id = $id";
    $resultado = mysqli_query($conn, $query);
    
    $_SESSION['message'] ='Dependencia actualizada';
    $_SESSION['message_type'] = 'warning';

    header("location: dependencia.php");
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

    <title>Dependencia</title>
</head>

<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Editar Dependencia</h1>
        </center>
    </header>
    <div class="container">
        <div class="col-md-3 mx-auto">
            <div class="form-container">
                    <form class="form" action="editardependencia.php?id=<?php echo $_GET['id']?>" method="POST">
                        <label>
                            <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z\s]+"
                                name="nombre" value="<?php echo $nombre; ?>">
                            <span>Nombre</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z]{2,}"
                                name="siglas" value="<?php echo $siglas; ?>">
                            <span>Siglas</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" name="direccion"
                                value="<?php echo $direccion; ?>">
                            <span>Direccion</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z\s]+"
                                name="titular" value="<?php echo $titular; ?>">
                            <span>Titular</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" pattern="\d{10}" name="telefono"
                                value="<?php echo $telefono; ?>">
                            <span>Telefono</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" name="enlace"
                                value="<?php echo $enlace; ?>">
                            <span>Enlace</span>
                        </label>

                        <label>
                            <input required="" placeholder="" type="text" class="input" pattern="\d{10}"
                                name="telefonoEnlace" value="<?php echo $telefonoEnlace; ?>">
                            <span>Telefono enlace</span>
                        </label>
                        <button class="btn btn-primary" href="#" name="actualizar">
                            <span class="top-key"></span>
                            <span class="text">Actualizar</span>
                            <span class="bottom-key-1"></span>
                            <span class="bottom-key-2"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>