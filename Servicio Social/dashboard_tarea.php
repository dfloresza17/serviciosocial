<?php 
include("bd.php"); 

// Consultas para obtener responsables, dependencias y estados
$query_responsables = "SELECT id, NombreResponsable FROM responsables";
$result_responsables = mysqli_query($conn, $query_responsables);

$query_dependencias = "SELECT id, Siglas FROM dependencia";
$result_dependencias = mysqli_query($conn, $query_dependencias);

// Definir opciones de estado de tarea
$estados_tarea = ['Pendiente', 'En Proceso', 'Completada', 'Cancelada'];

// Variables para los filtros
$responsable_filter = isset($_GET['responsable']) ? $_GET['responsable'] : '';
$dependencia_filter = isset($_GET['dependencia']) ? $_GET['dependencia'] : '';
$mes_filter = isset($_GET['mes']) ? $_GET['mes'] : '';
$estado_filter = isset($_GET['estado']) ? $_GET['estado'] : '';

// Consulta SQL modificada para aplicar los filtros si están definidos
$query = "
    SELECT 
        tareas.id AS tarea_id, 
        tareas.NombreTarea, 
        tareas.EstadoTarea, 
        GROUP_CONCAT(DISTINCT observaciones.observacion ORDER BY observaciones.fecha_observacion ASC SEPARATOR '<br>-') AS Observaciones, 
        GROUP_CONCAT(DISTINCT observaciones.fecha_observacion ORDER BY observaciones.fecha_observacion ASC SEPARATOR '<br>') AS FechasObservaciones,
        tareas.FechaInicio, 
        tareas.FechaFinalizacion, 
        responsables.NombreResponsable, 
        GROUP_CONCAT(DISTINCT dependencia.Siglas SEPARATOR ', ') AS Dependencias
    FROM tareas 
    LEFT JOIN responsables ON tareas.ResponsableId = responsables.id 
    LEFT JOIN tareas_dependencias ON tareas.id = tareas_dependencias.TareaId 
    LEFT JOIN dependencia ON tareas_dependencias.DependenciaId = dependencia.id 
    LEFT JOIN tareas_observaciones observaciones ON tareas.id = observaciones.tarea_id
    WHERE 1 = 1";

// Agregar filtros
if ($responsable_filter != '') {
    $query .= " AND tareas.ResponsableId = '$responsable_filter'";
}
if ($dependencia_filter != '') {
    $query .= " AND dependencia.id = '$dependencia_filter'";
}
if ($mes_filter != '') {
    $query .= " AND MONTH(tareas.FechaInicio) = '$mes_filter'";
}
if ($estado_filter != '') {
    $query .= " AND tareas.EstadoTarea = '$estado_filter'";
}

$query .= " GROUP BY tareas.id";

// Ejecutar la consulta
$result = mysqli_query($conn, $query);

// Verificar si hubo un error en la consulta
if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
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

    <title>Tarea</title>
</head>

<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Calendario Tareas</h1>
        </center>
    </header>

    <nav class="custom-nav">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a href="#" class="nav-link active"><i class="fa-solid fa-calendar-days"></i></a></center>
            </li>
            <li class="nav-item">
                <a href="tarea.php" class="nav-link"><i class="fa-solid fa-file-pen"></i></a></center>
            </li>
        </ul>
    </nav>

    <div class="container">
        <!-- Filtros -->
        <form method="GET" action="">
            <div class="col-md-7">
                <!-- Filtro por Responsable -->
                <div class="col">
                    <label for="responsable-filter" class="form-label">Responsable:</label>
                    <select name="responsable" id="responsable-filter" class="form-select">
                        <option value="">Todos</option>
                        <?php while ($row_responsable = mysqli_fetch_assoc($result_responsables)) { ?>
                        <option value="<?php echo $row_responsable['id']; ?>"
                            <?php echo $responsable_filter == $row_responsable['id'] ? 'selected' : ''; ?>>
                            <?php echo $row_responsable['NombreResponsable']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Filtro por Dependencia -->
                <div class="col">
                    <label for="dependencia-filter" class="form-label">Dependencia:</label>
                    <select name="dependencia" id="dependencia-filter" class="form-select">
                        <option value="">Todas</option>
                        <?php while ($row_dependencia = mysqli_fetch_assoc($result_dependencias)) { ?>
                        <option value="<?php echo $row_dependencia['id']; ?>"
                            <?php echo $dependencia_filter == $row_dependencia['id'] ? 'selected' : ''; ?>>
                            <?php echo $row_dependencia['Siglas']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Filtro por Estado -->
                <div class="col">
                    <label for="estado-filter" class="form-label">Estado:</label>
                    <select name="estado" id="estado-filter" class="form-select">
                        <option value="">Todos</option>
                        <option value="no iniciada" <?php echo $estado_filter == 'no iniciada' ? 'selected' : ''; ?>>No
                            iniciada</option>
                        <option value="en pausa" <?php echo $estado_filter == 'en pausa' ? 'selected' : ''; ?>>En pausa
                        </option>
                        <option value="en proceso" <?php echo $estado_filter == 'en proceso' ? 'selected' : ''; ?>>En
                            proceso</option>
                        <option value="finalizada" <?php echo $estado_filter == 'finalizada' ? 'selected' : ''; ?>>
                            Finalizada</option>
                        <option value="cancelada" <?php echo $estado_filter == 'cancelada' ? 'selected' : ''; ?>>
                            Cancelada</option>
                    </select>
                </div>

                <!-- Botón de aplicar filtros -->
                <div class="col">
                    <br> <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                </div>
            </div>
        </form>





        <div class="col-md-10">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Nombre tarea</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Fechas Observaciones</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de finalizacion</th>
                        <th>Responsable</th>
                        <th>Dependencia</th>
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
                        <td><?php echo $row['FechasObservaciones']; ?></td>
                        <td><?php echo $row['FechaInicio']; ?></td>
                        <td><?php echo $row['FechaFinalizacion']; ?></td>
                        <td><?php echo $row['NombreResponsable']; ?></td>
                        <td><?php echo $row['Dependencias']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>