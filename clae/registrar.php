<?php
include 'conexion.php';
include 'menu.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $reg_matricula = $_POST['reg_matricula'];
    $reg_carrera = $_POST['reg_carrera'];
    $reg_nombre = $_POST['reg_nombre'];
    $reg_apellidop = $_POST['reg_apellidop'];
    $reg_apellidom = $_POST['reg_apellidom'];
    $reg_correo = $_POST['reg_correo'];
    $reg_telefono = $_POST['reg_telefono'];

    // Insertar datos en la base de datos
    $sql = "INSERT INTO registro (reg_matricula, reg_carrera, reg_nombre, reg_apellidop, reg_apellidom, reg_correo, reg_telefono)
            VALUES ('$reg_matricula', '$reg_carrera', '$reg_nombre', '$reg_apellidop', '$reg_apellidom', '$reg_correo', '$reg_telefono')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro creado exitosamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registrar.css">
    <title>Registro</title>
</head>

<body>
    <div class="contenedor">
        <form method="post" action="">
            <div class="contenedorSecundario">
                <h1>Formulario de Registro</h1>
                <div class="campo">
                    <label for="reg_matricula">Clave:</label>
                    <input type="text" id="reg_matricula" name="reg_matricula" required>
                </div>
                <div class="campo">
                    <label for="reg_carrera">Carrera:</label>
                    <select id="reg_carrera" name="reg_carrera" required>
                        <option value="Sistemas Computacionales">Sistemas Computacionales</option>
                        <option value="Medicina">Medicina</option>
                        <option value="Ingeniería de Software">Ingeniería de Software</option>
                        <option value="Psicología">Psicología</option>
                        <option value="Nutrición">Nutrición</option>
                        <option value="Educación Física">Educación Física</option>
                    </select>
                </div>
                <div class="campo">
                    <label for="reg_nombre">Nombre:</label>
                    <input type="text" id="reg_nombre" name="reg_nombre" required>
                </div>
                <div class="campo">
                    <label for="reg_apellidop">Apellido Paterno:</label>
                    <input type="text" id="reg_apellidop" name="reg_apellidop" required>
                </div>
                <div class="campo">
                    <label for="reg_apellidom">Apellido Materno:</label>
                    <input type="text" id="reg_apellidom" name="reg_apellidom" required>
                </div>
                <div class="campo">
                    <label for="reg_correo">Correo:</label>
                    <input type="email" id="reg_correo" name="reg_correo" required>
                </div>
                <div class="campo">
                    <label for="reg_telefono">Teléfono:</label>
                    <input type="text" id="reg_telefono" name="reg_telefono" required>
                </div>
                <div class="boton-container">
                    <button type="submit">Registrar</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>