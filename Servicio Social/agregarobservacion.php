<?php
include('bd.php'); // Conexión a la base de datos

// Obtener el ID de la tarea desde la URL
$tarea_id = $_GET['id'];

// Si el formulario es enviado
if (isset($_POST['agregar_observacion'])) {
    $nueva_observacion = $_POST['Observacion'];

    // Insertar la nueva observación en la base de datos
    $query = "INSERT INTO tareas_observaciones (tarea_id, observacion) VALUES ($tarea_id, '$nueva_observacion')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['message'] = 'Observación agregada exitosamente';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error al agregar la observación';
        $_SESSION['message_type'] = 'danger';
    }

    // Redirigir al listado de tareas o donde prefieras
    header("Location: tarea.php");
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
    <title>Agregar Observacion</title>
</head>
<body>
    <header class="header">
        <center>
            <h1>Sistema de Seguimiento de Tareas</h1>
            <h1>Agregar Observacion</h1>
        </center>
    </header>
    <div class="container">
        <div class="col-md-3 mx-auto">
            <!-- Mostrar mensaje de éxito o error -->
            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php session_unset(); endif; ?>

            <!-- Formulario para agregar la observación -->
            <div class="form-container">
                <form class="form" action="agregarobservacion.php?id=<?php echo $tarea_id; ?>" method="POST">

                    <!-- Cuadro de texto para la nueva observación -->
                    <label>
                        <textarea required cols="40" class="input" rows="9" name="Observacion"
                            placeholder="Escribe una nueva observación"></textarea>
                    </label>

                    <!-- Botón para enviar la nueva observación -->
                    <button class="fancy" type="submit" name="agregar_observacion">
                        <span class="top-key"></span>
                        <span class="text">Agregar Observación</span>
                        <span class="bottom-key-1"></span>
                        <span class="bottom-key-2"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
