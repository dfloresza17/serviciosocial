<?php 
include("bd.php");
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
            <h1>Dependencia</h1>
        </center>
    </header>
    <nav class="custom-nav">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Dependencia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Responsable.php">Responsable</a>
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
                <form class="form" action="guardardependecia.php" method="POST">
                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z\s]+" name="nombre">
                        <span>Nombre</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z]{2,}"
                            name="siglas">
                        <span>Siglas</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="direccion">
                        <span>Direccion</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="[A-Za-z\s]+"
                            name="titular">
                        <span>Titular</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="\d{10}" name="telefono">
                        <span>Telefono</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" name="enlace">
                        <span>Enlace</span>
                    </label>

                    <label>
                        <input required="" placeholder="" type="text" class="input" pattern="\d{10}"
                            name="telefonoEnlace">
                        <span>Telefono enlace</span>
                    </label>

                    <!-- <label>
                <textarea required="" rows="3" placeholder="" class="input01"></textarea>
                <span>message</span>
            </label>-->

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
                        <th>Nombre</th>
                        <th>Siglas</th>
                        <th>Direccion</th>
                        <th>Titular</th>
                        <th>Telefono</th>
                        <th>Nombre de Enlace</th>
                        <th>Telefono enlace</th>
                        <th>Borrar Dependencia</th>
                        <th>Editar Dependencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query ="SELECT * FROM dependencia";
                    $resultados = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_array($resultados)){?>
                    <tr>
                        <td><?php echo $row['Nombre']?></td>
                        <td><?php echo $row['Siglas']?></td>
                        <td><?php echo $row['Direccion']?></td>
                        <td><?php echo $row['Titular']?></td>
                        <td><?php echo $row['Telefono']?></td>
                        <td><?php echo $row['Enlace']?></td>
                        <td><?php echo $row['TelefonoEnlace']?></td>
                        <td>
                            <center><a href="eliminardependencia.php?id=<?php echo $row['id']?>" class="btn btn-danger"><i
                                        class="fa-regular fa-trash-can"></i></a></center>
                        </td>
                        <td>
                            <center><a href="editardependencia.php?id=<?php echo $row['id']?>" class="btn btn-secondary"><i
                                        class="fa-regular fa-pen-to-square"></i></a></center>
                        </td>
                    </tr>
                    <?php }?>


                </tbody>
            </table>
        </div>
    </div>
</body>

</html>