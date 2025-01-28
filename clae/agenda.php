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
        echo "<p style='color: red;'>El horario seleccionado ya está ocupado. Por favor, elige otro.</p>";
    } else {
        $sql = "INSERT INTO recepcionm (REC_Fecha, REC_Matricula, REC_Carrera, REC_Nombre,  REC_Area, REC_Horaini, REC_Horafin)
                VALUES ('$fecha', '$matricula', '$carrera', '$nombre', '$area', '$fechaHora', DATE_ADD('$fechaHora', INTERVAL 30 MINUTE))";

        if ($conn->query($sql) === TRUE) {
            header('Location: recepcion.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Matrícula y Agregar Registro</title>
    <script>
        function generarHorarios() {
            const diaSemana = new Date(document.querySelector('input[name="REC_Fecha"]').value).getDay(); 
            const areaSeleccionada = document.querySelector('select[name="REC_Area"]').value;

            const horariosDisponibles = {
                Nutricion: {
                    default: [
                        "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                        "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                        "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                        "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
                    ]
                },
                Medicina: {
                    default: [
                        "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                        "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                        "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                        "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
                    ]
                },
                Psicologia: {
                    default: [
                        "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                        "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                        "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                        "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
                    ]
                }
            };

            const selectHorarios = document.getElementById('REC_Horario');
            selectHorarios.innerHTML = ""; 

            const horarios =
                (horariosDisponibles[areaSeleccionada] &&
                 horariosDisponibles[areaSeleccionada][diaSemana]) ||
                horariosDisponibles[areaSeleccionada]?.default || [];

            horarios.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                selectHorarios.appendChild(option);
            });
        }
    </script>
</head>
<body>
    <h1>Validar Matrícula y Agregar Registro</h1>

    <?php if ($mensaje): ?>
        <p style="color: red;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="reg_matricula">Ingrese Matrícula:</label>
        <input type="number" id="reg_matricula" name="reg_matricula" required>
        <button type="submit" name="buscar">Buscar</button>
    </form>

    <br>

    <?php if ($usuario): ?>
        <form action="" method="POST">
            <label>Matrícula: <input type="number" name="REC_Matricula" value="<?php echo $usuario['reg_matricula']; ?>" required></label><br>
            <label>Carrera: <input type="text" name="REC_Carrera" value="<?php echo $usuario['reg_carrera']; ?>" required></label><br>
            <label>Nombre: <input type="text" name="REC_Nombre" value="<?php echo $usuario['reg_nombre']; ?>" required></label><br>
            <label>Apellidos: <input type="text" name="REC_Apellidos" value="<?php echo $usuario['reg_apellidop'] . ' ' . $usuario['reg_apellidom']; ?>" required></label><br>
            <label>Fecha para agendar: 
                <input type="date" name="REC_Fecha" onchange="generarHorarios()" required>
            </label><br>
            <label>Área:
                <select name="REC_Area" onchange="generarHorarios()" required>
                    <option value="Nutricion">Nutrición</option>
                    <option value="Medicina">Medicina</option>
                    <option value="Psicologia">Psicología</option>
                </select>
            </label><br>
            <label>Horario:
                <select id="REC_Horario" name="REC_Horario" required>
                </select>
            </label><br>
            <button type="submit" name="submit">Agregar</button>
        </form>
    <?php endif; ?>
</body>
</html>
