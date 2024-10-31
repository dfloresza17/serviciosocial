<?php 
include("bd.php"); 

// Consultas para obtener responsables y dependencias
$query_responsables = "SELECT id, NombreResponsable FROM responsables";
$result_responsables = mysqli_query($conn, $query_responsables);

$query_dependencias = "SELECT id, Siglas FROM dependencia";
$result_dependencias = mysqli_query($conn, $query_dependencias);
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

    <title>Tarea</title>
</head>

<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Tarea</h1>
        </center>
    </header>

    <nav class="custom-nav">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="Dependencia.php">Dependencia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Responsable.php">Responsable</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Tarea</a>
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
                <form class="form" action="guardartarea.php" method="POST">
                    <label>
                        <input required="" placeholder="" type="text" class="input" name="NombreTarea"
                            pattern="[A-Za-z\s]+">
                        <span>Nombre Tarea</span>
                    </label>

                    <label for="custom-select">
                        <select id="custom-select" class="inputselect" name="EstadoTarea" required="">
                            <option value="">Seleccione el Estado de la tarea</option>
                            <option value="no iniciada">No iniciada</option>
                            <option value="en pausa">En pausa</option>
                            <option value="en proceso">En proceso</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </label>

                    <!--<label>
                        <textarea required="" cols="40" class="input" rows="9" name="Observaciones"
                            placeholder="Escribe las observaciones"></textarea>
                    </label>-->

                    <label>
                        <span>Fecha de inicio</span>
                        <input required="" placeholder="" type="date" class="input" name="FechaInicio">
                    </label>

                    <label>
                        <span>Fecha de finalización</span>
                        <input required="" placeholder="" type="date" class="input" name="FechaFinalizacion">
                    </label>

                    <label for="custom-select">
                        <select id="custom-select" class="inputselect" name="ResponsableId" required="">
                            <option value="">Seleccione responsable</option>
                            <?php while($row = mysqli_fetch_assoc($result_responsables)) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['NombreResponsable']; ?></option>
                            <?php } ?>
                        </select>
                    </label>

                    <label>Seleccione dependencias vinculadas:</label>
                    <div>
                        <?php while ($row = mysqli_fetch_assoc($result_dependencias)) { ?>
                        <label>
                            <input type="checkbox" name="DependenciaId[]" value="<?php echo $row['id']; ?>">
                            <?php echo $row['Siglas']; ?>
                        </label><br>
                        <?php } ?>
                    </div>



                    <button class="fancy" type="submit" name="guardar">
                        <span class="top-key"></span>
                        <span class="text">Guardar</span>
                        <span class="bottom-key-1"></span>
                        <span class="bottom-key-2"></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-10">
            <table class="table table-sm">
                <?php
    // Consulta SQL modificada para evitar duplicados
    $query = "
    SELECT 
        tareas.id AS tarea_id, 
        tareas.NombreTarea, 
        tareas.EstadoTarea, 
        GROUP_CONCAT(DISTINCT observaciones.observacion ORDER BY observaciones.fecha_observacion ASC SEPARATOR '<br>-') AS Observaciones, 
        tareas.FechaInicio, 
        tareas.FechaFinalizacion, 
        responsables.NombreResponsable, 
        GROUP_CONCAT(DISTINCT dependencia.Siglas SEPARATOR ', ') AS Dependencias
    FROM tareas 
    LEFT JOIN responsables ON tareas.ResponsableId = responsables.id 
    LEFT JOIN tareas_dependencias ON tareas.id = tareas_dependencias.TareaId 
    LEFT JOIN dependencia ON tareas_dependencias.DependenciaId = dependencia.id 
    LEFT JOIN tareas_observaciones observaciones ON tareas.id = observaciones.tarea_id
    GROUP BY tareas.id
";

    
    // Ejecutar la consulta
    $result = mysqli_query($conn, $query);
    
    // Verificar si hubo un error en la consulta
    if (!$result) {
        die("Error en la consulta SQL: " . mysqli_error($conn));
    }
    ?>

                <thead>
                    <tr>
                        <th>Nombre tarea</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de finalizacion</th>
                        <th>Responsable</th>
                        <th>Dependencia</th>
                        <th>Editar tarea</th>
                        <th>Agregar observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
        // Bucle para mostrar cada fila de la consulta
        while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['NombreTarea']; ?></td>
                        <td><?php echo $row['EstadoTarea']; ?></td>
                        <td>-<?php echo $row['Observaciones']; ?></td>
                        <td><?php echo $row['FechaInicio']; ?></td>
                        <td><?php echo $row['FechaFinalizacion']; ?></td>
                        <td><?php echo $row['NombreResponsable']; ?></td>
                        <td><?php echo $row['Dependencias']; ?></td>
                        <td>
                            <center><a href="editartarea.php?id=<?php echo $row['tarea_id']; ?>"
                                    class="btn btn-secondary">
                                    <i class="fa-regular fa-pen-to-square"></i></a></center>
                        </td>
                        <td>
                            <center><a href="agregarobservacion.php?id=<?php echo $row['tarea_id']; ?>"
                                    class="btn btn-success">
                                    <i class="fa-regular fa-plus"></i></a></center>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>