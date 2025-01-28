<?php
include("conexion.php");
include 'menu.php';

$fechaSeleccionada = date('Y-m-d'); // Fecha por defecto (hoy)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaSeleccionada = $_POST['fecha']; // Actualiza la fecha seleccionada
    
    // Verifica si se presionó el botón "Atendido"
    if (isset($_POST['atendido'])) {
        $idAtendido = $_POST['atendido']; // Obtiene el ID de la consulta que se marcará como "Atendido"
        // Actualiza el estado del registro en la base de datos
        $sqlAtendido = "UPDATE recepcionm SET REC_Status = 'Atendido' WHERE REC_ID = ?";
        $stmtAtendido = $conn->prepare($sqlAtendido);
        $stmtAtendido->bind_param("i", $idAtendido); // Vincula el ID de la consulta a la consulta SQL
        $stmtAtendido->execute(); // Ejecuta la actualización
        $stmtAtendido->close(); // Cierra la sentencia preparada
    }
}

// Consulta para obtener los registros de Nutricion en la fecha seleccionada
$sql = "SELECT * FROM recepcionm WHERE REC_Area = 'Nutricion' AND REC_Fecha = ? ORDER BY REC_Horaini";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fechaSeleccionada);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Nutricion</title>
</head>
<body>
    <h1>Agenda de Nutricion</h1>

    <form method="POST" action="">
        <label for="fecha">Seleccione una fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo $fechaSeleccionada; ?>" required>
        <button type="submit">Filtrar</button>
    </form>

    <h2>Consultas para el <?php echo $fechaSeleccionada; ?></h2>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Matrícula</th>
                <th>Carrera</th>
                <th>Nombre</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['REC_ID']; ?></td>
                        <td><?php echo $row['REC_Fecha']; ?></td>
                        <td><?php echo $row['REC_Matricula']; ?></td>
                        <td><?php echo $row['REC_Carrera']; ?></td>
                        <td><?php echo $row['REC_Nombre']; ?></td>
                        <td><?php echo $row['REC_Horaini']; ?></td>
                        <td><?php echo $row['REC_Horafin']; ?></td>
                        <td>
                            <?php if ($row['REC_Status'] !== 'Atendido'): ?>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="fecha" value="<?php echo $fechaSeleccionada; ?>">
                                    <button type="submit" name="atendido" value="<?php echo $row['REC_ID']; ?>">Atendido</button>
                                </form>
                            <?php else: ?>
                                Atendido
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay consultas programadas para esta fecha.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
