<?php 
session_start();
include("bd.php");

if (isset($_POST['guardar'])) {
    $nombreTarea = $_POST['NombreTarea'];
    $estadoTarea = $_POST['EstadoTarea'];
    $observaciones = $_POST['Observaciones'];
    $fechaInicio = $_POST['FechaInicio'];
    $fechaFinalizacion = $_POST['FechaFinalizacion'];
    $responsableId = $_POST['ResponsableId'];
    $dependenciaIds = $_POST['DependenciaId']; // Puede ser un solo ID o un array

    // Debug: Imprimir valores obtenidos
    echo "Nombre tarea: $nombreTarea<br>";
    echo "Estado tarea: $estadoTarea<br>";
    echo "Observaciones: $observaciones<br>";
    echo "Fecha inicio: $fechaInicio<br>";
    echo "Fecha finalización: $fechaFinalizacion<br>";
    echo "Responsable ID: $responsableId<br>";
    echo "Dependencia IDs: " . implode(', ', (array)$dependenciaIds) . "<br>";

    // Consulta SQL para insertar una nueva tarea
    $query = "INSERT INTO tareas (NombreTarea, EstadoTarea, Observaciones, FechaInicio, FechaFinalizacion, ResponsableId) 
              VALUES ('$nombreTarea', '$estadoTarea', '$observaciones', '$fechaInicio', '$fechaFinalizacion', '$responsableId')";
    
    $resultado = mysqli_query($conn, $query);

    if (!$resultado) {
        die("Error al guardar la tarea: " . mysqli_error($conn));
    }

    // Obtener el ID de la tarea recién insertada
    $tareaId = mysqli_insert_id($conn);

    // Insertar las dependencias vinculadas a la tarea en la tabla pivot (tarea_dependencia)
    foreach ((array)$dependenciaIds as $dependenciaId) {
        $query_dependencia = "INSERT INTO tareas_dependencias (TareaId, DependenciaId) VALUES ($tareaId, $dependenciaId)";
        $resultado_dependencia = mysqli_query($conn, $query_dependencia);

        if (!$resultado_dependencia) {
            die("Error al guardar la relación entre la tarea y la dependencia: " . mysqli_error($conn));
        }
    }

    // Mensaje de éxito
    $_SESSION['message'] = 'Tarea guardada';
    $_SESSION['message_type'] = 'success';

    // Redirigir a la página de tareas
    header("location: Tarea.php");
}
?>
