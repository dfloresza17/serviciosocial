<?php
include("bd.php");

// Obtener el ID de la tarea a editar
$tarea_id = isset($_GET['id']) ? $_GET['id'] : null;

// Consulta para obtener responsables y dependencias
$sql_responsables = "SELECT id, NombreResponsable FROM responsables";
$resultado_responsables = mysqli_query($conn, $sql_responsables);

$sql_dependencias = "SELECT id, Siglas FROM dependencia";
$result_dependencias = mysqli_query($conn, $sql_dependencias);

// Dependencias seleccionadas para la tarea actual
$dependencias_seleccionadas = [];
if ($tarea_id) {
    $sql_dependencias_seleccionadas = "SELECT DependenciaId FROM tareas_dependencias WHERE TareaId = $tarea_id";
    $result_dependencias_seleccionadas = mysqli_query($conn, $sql_dependencias_seleccionadas);

    while ($row = mysqli_fetch_assoc($result_dependencias_seleccionadas)) {
        $dependencias_seleccionadas[] = $row['DependenciaId'];
    }
}

// Si se ha pasado un id por la URL, obtener los datos de la tarea
if ($tarea_id) {
    $query = "SELECT * FROM tareas WHERE id = $tarea_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $nombre_tarea = $row['NombreTarea'];
        $estado_tarea = $row['EstadoTarea'];
        $observaciones = $row['Observaciones'];
        $fecha_inicio = $row['FechaInicio'];
        $fecha_fin = $row['FechaFinalizacion'];
        $responsable_id = $row['ResponsableId'];
    }
}

// Si se envía el formulario de actualización
if (isset($_POST['actualizar'])) {
    // Capturar los datos enviados por el formulario
    $nombre_tarea = $_POST['NombreTarea'];
    $estado_tarea = $_POST['EstadoTarea'];
    $observaciones = $_POST['Observaciones'];
    $fecha_inicio = $_POST['FechaInicio'];
    $fecha_fin = $_POST['FechaFinalizacion'];
    $responsable_id = $_POST['ResponsableId'];

    // Actualizar la tarea
    $query = "UPDATE tareas SET NombreTarea = '$nombre_tarea', EstadoTarea = '$estado_tarea', Observaciones = '$observaciones', 
              FechaInicio = '$fecha_inicio', FechaFinalizacion = '$fecha_fin', ResponsableId = $responsable_id WHERE id = $tarea_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Actualizar dependencias de la tarea
        $query_delete_dependencias = "DELETE FROM tareas_dependencias WHERE TareaId = $tarea_id";
        mysqli_query($conn, $query_delete_dependencias);

        if (!empty($_POST['DependenciaId'])) {
            foreach ($_POST['DependenciaId'] as $dependencia_id) {
                $query_insert_dependencia = "INSERT INTO tareas_dependencias (TareaId, DependenciaId) VALUES ($tarea_id, $dependencia_id)";
                mysqli_query($conn, $query_insert_dependencia);
            }
        }

        $_SESSION['message'] = 'Tarea actualizada exitosamente';
        $_SESSION['message_type'] = 'warning';
    } else {
        $_SESSION['message'] = 'Error al actualizar la tarea';
        $_SESSION['message_type'] = 'danger';
    }

    header("location: tarea.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2b23531528.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>Editar Tarea</title>
</head>
<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Editar Tarea</h1>
        </center>
    </header>
    <div class="container">
        <div class="col-md-3 mx-auto">
            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php session_unset(); endif; ?>

            <div class="form-container">
                <form class="form" action="editartarea.php?id=<?php echo $tarea_id; ?>" method="POST">
                    <label>
                        <input required type="text" class="input" name="NombreTarea" value="<?php echo $nombre_tarea; ?>" pattern="[A-Za-z\s]+">
                        <span>Nombre Tarea</span>
                    </label>

                    <label for="estado-tarea">
                        <select id="estado-tarea" class="inputselect" name="EstadoTarea" required>
                            <option value="">Seleccione el Estado de la tarea</option>
                            <option value="no iniciada" <?php if ($estado_tarea == 'no iniciada') echo 'selected'; ?>>No iniciada</option>
                            <option value="en pausa" <?php if ($estado_tarea == 'en pausa') echo 'selected'; ?>>En pausa</option>
                            <option value="en proceso" <?php if ($estado_tarea == 'en proceso') echo 'selected'; ?>>En proceso</option>
                            <option value="finalizada" <?php if ($estado_tarea == 'finalizada') echo 'selected'; ?>>Finalizada</option>
                            <option value="cancelada" <?php if ($estado_tarea == 'cancelada') echo 'selected'; ?>>Cancelada</option>
                        </select>
                    </label>

                    <!--<label>
                        <textarea required cols="40" class="input" rows="9" name="Observaciones"><?php echo $observaciones; ?></textarea>
                    </label>-->

                    <label>
                        <span>Fecha de inicio</span>
                        <input required type="date" class="input" name="FechaInicio" value="<?php echo $fecha_inicio; ?>">
                    </label>

                    <label>
                        <span>Fecha de finalización</span>
                        <input required type="date" class="input" name="FechaFinalizacion" value="<?php echo $fecha_fin; ?>">
                    </label>

                    <label for="responsable-id">
                        <select id="responsable-id" class="inputselect" name="ResponsableId" required>
                            <option value="">Seleccione responsable</option>
                            <?php while($row = mysqli_fetch_assoc($resultado_responsables)): ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $responsable_id) echo 'selected'; ?>>
                                <?php echo $row['NombreResponsable']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </label>

                    <label>Seleccione dependencias vinculadas:</label>
                    <div>
                        <?php while ($row = mysqli_fetch_assoc($result_dependencias)): ?>
                        <label>
                            <input type="checkbox" name="DependenciaId[]" value="<?php echo $row['id']; ?>" 
                                <?php if (in_array($row['id'], $dependencias_seleccionadas)) echo 'checked'; ?>>
                            <?php echo $row['Siglas']; ?>
                        </label><br>
                        <?php endwhile; ?>
                    </div>

                    <button class="fancy" type="submit" name="actualizar">
                        <span class="top-key"></span>
                        <span class="text">Actualizar</span>
                        <span class="bottom-key-1"></span>
                        <span class="bottom-key-2"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
