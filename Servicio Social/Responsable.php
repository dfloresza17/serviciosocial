<?php
    include("bd.php");

    $sql = "SELECT id, Siglas FROM dependencia";
    $resultado = mysqli_query($conn, $sql);
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

    <title>Responsable</title>
</head>

<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Responsable</h1>
        </center>
    </header>
    <nav class="custom-nav">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" href="Dependencia.php">Dependencia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Responsable</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Tarea.php">Tarea</a>
            </li>
            <li class="nav-item">
                <a href="dashboard_tarea.php" class="nav-link"><i class="fa-solid fa-calendar-days"></i></a></center>
            </li>
        </ul>
    </nav>
    <div class="container">
        <div class="col-md-3">
            <?php if (isset($_SESSION['message'])) {?>
            <div class="alert alert-<?=$_SESSION['message_type'];?> alert-dismissible fade show" role="alert">
                <?=$_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php session_unset(); }?>
            <div class="form-container">
                <form class="form" action="guardarresponsable.php" method="POST">
                    <label>
                        <input required="" placeholder="" type="text" class="input" name="NombreResponsable">
                        <span>Nombre Responsable</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="Cargo">
                        <span>Cargo</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="Area">
                        <span>Area</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="\d{10}" name="Telefono">
                        <span>Telefono</span>
                    </label>

                    <label for="custom-select">
                        <select id="custom-select" class="inputselect" name="dependencia">
                            <option value="0">Seleccione la dependencia</option>
                            <?php
            if ($resultado->num_rows > 0) {
                // Generar las opciones del select
                while($row = $resultado->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['Siglas'].'</option>';
                }
            } else {
                echo '<option value="0">No hay dependencias disponibles</option>';
            }
            ?>
                        </select>
                    </label>
                    <button class="fancy" href="#" name="guardar">
                        <span class="top-key"></span>
                        <span class="text">Guardar</span>
                        <span class="bottom-key-1"></span>
                        <span class="bottom-key-2"></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nombre del Responsable</th>
                        <th>Cargo</th>
                        <th>Area</th>
                        <th>Telefono</th>
                        <th>Dependencia</th>
                        <th>Borrar Responsable</th>
                        <th>Editar Responsable</th>
                    </tr>
                </thead>
                <?php
$sql_responsables = "SELECT r.id, r.NombreResponsable, r.Cargo, r.Area, r.Telefono, d.Siglas 
                     FROM responsables r
                     JOIN dependencia d ON r.dependencia = d.id
                     ORDER BY r.id ASC";  
$resultado_responsables = mysqli_query($conn, $sql_responsables);
?>

                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado_responsables)) { ?>
                    <tr>
                        <td><?php echo $row['NombreResponsable']; ?></td>
                        <td><?php echo $row['Cargo']; ?></td>
                        <td><?php echo $row['Area']; ?></td>
                        <td><?php echo $row['Telefono']; ?></td>
                        <td><?php echo $row['Siglas']; ?></td>
                        <td>
                            <center><a href="eliminarresponsable.php?id=<?php echo $row['id']?>" class="btn btn-danger"><i
                                        class="fa-regular fa-trash-can"></i></a></center>
                        </td>
                        <td>
                            <center><a href="editarresponsable.php?id=<?php echo $row['id']?>" class="btn btn-secondary"><i
                                        class="fa-regular fa-pen-to-square"></i></a></center>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</body>

</html>