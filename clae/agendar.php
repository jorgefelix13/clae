<?php
include("conexion.php");
include 'menu.php';

$usuario = null;
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
    $matricula = $_POST['reg_matricula'];

    $sql = "SELECT * FROM registro WHERE reg_matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $mensaje = "La matrícula de la persona no se encuentra, se tiene que registrar.";
    }
    $stmt->close();
}

if (isset($_POST['submit'])) {
    $fecha = $_POST['REC_Fecha'];
    $matricula = $_POST['REC_Matricula'];
    $carrera = $_POST['REC_Carrera'];
    $nombre = $_POST['REC_Nombre'];
    $apellidos = $_POST['REC_Apellidos'];
    $area = $_POST['REC_Area'];
    $horario = $_POST['REC_Horario'];

    $fechaHora = "$fecha $horario:00";

    $sqlCheck = "SELECT * FROM recepcionm WHERE REC_Fecha = '$fecha' AND REC_Horaini = '$fechaHora' AND REC_Area = '$area'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        echo "<p class='error'>El horario seleccionado ya está ocupado. Por favor, elige otro.</p>";
    } else {
        $sql = "INSERT INTO recepcionm (REC_Fecha, REC_Matricula, REC_Carrera, REC_Nombre, REC_Apellidos, REC_Area, REC_Horaini, REC_Horafin) VALUES ('$fecha', '$matricula', '$carrera', '$nombre', '$apellidos', '$area', '$fechaHora', DATE_ADD('$fechaHora', INTERVAL 30 MINUTE))";

        if ($conn->query($sql) === TRUE) {
            header('Location: agendar.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$agendaAreas = ["Nutricion", "Medicina", "Psicologia"];
$agendas = [];
foreach ($agendaAreas as $area) {
    $agendaSql = "SELECT * FROM recepcionm WHERE REC_Area = '$area' ORDER BY REC_Fecha, REC_Horaini";
    $agendaResult = $conn->query($agendaSql);
    $agendas[$area] = $agendaResult;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Matrícula y Agregar Registro</title>
    <link rel="stylesheet" href="agendar.css">
</head>

<body>
    

    <main>
        <section class="search">
            <h2>Búsqueda de Matrícula</h2>
            <form method="POST" action="">
                <label for="reg_matricula">Ingrese Matrícula:</label>
                <input type="number" id="reg_matricula" name="reg_matricula" required>
                <button type="submit" name="buscar">Buscar</button>
            </form>
            <?php if ($mensaje): ?>
                <p class="error"> <?php echo $mensaje; ?> </p>
            <?php endif; ?>
        </section>

        <?php if ($usuario): ?>
            <section class="registro">
                <h2>Registrar Cita</h2>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Matrícula:</label>
                        <input type="number" name="REC_Matricula" value="<?php echo $usuario['reg_matricula']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Carrera:</label>
                        <input type="text" name="REC_Carrera" value="<?php echo $usuario['reg_carrera']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="REC_Nombre" value="<?php echo $usuario['reg_nombre']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos:</label>
                        <input type="text" name="REC_Apellidos" value="<?php echo $usuario['reg_apellidop'] . ' ' . $usuario['reg_apellidom']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" name="REC_Fecha" required>
                    </div>
                    <div class="form-group">
                        <label>Área:</label>
                        <select name="REC_Area" required>
                            <option value="Nutricion">Nutrición</option>
                            <option value="Medicina">Medicina</option>
                            <option value="Psicologia">Psicología</option>
                        </select>
                    </div>
                    <button type="submit" name="submit">Agregar</button>
                </form>
            </section>
        <?php endif; ?>

        <section class="agenda">
            <h2 id="subtitulo">Agenda por Áreas</h2>
            <?php foreach ($agendas as $area => $agendaResult): ?>
                <h3><?php echo $area; ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Matrícula</th>
                            <th>Carrera</th>
                            <th>Nombre</th>
                            <th>Área</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $agendaResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['REC_ID']; ?></td>
                                <td><?php echo $row['REC_Fecha']; ?></td>
                                <td><?php echo $row['REC_Matricula']; ?></td>
                                <td><?php echo $row['REC_Carrera']; ?></td>
                                <td><?php echo $row['REC_Nombre']; ?></td>
                                <td><?php echo $row['REC_Area']; ?></td>
                                <td><?php echo $row['REC_Horaini']; ?></td>
                                <td><?php echo $row['REC_Horafin']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>