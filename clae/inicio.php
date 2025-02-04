<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = $conn->prepare("SELECT * FROM usuarios WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirige basado en el rol del usuario
        switch ($user['role']) {
            case 'administrador':
                header("Location: administrador.php");
                break;
            case 'recepcion':
                header("Location: recepcion.php");
                break;
            case 'doctor':
                header("Location: doctor.php");
                break;
            case 'nutricion':
                header("Location: nutricion.php");
                break;
            case 'psicologia':
                header("Location: psicologia.php");
                break;
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="inicio.css">
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
</head>

<body>
    <form method="POST" action="">

        <div class="encabezado">
            <div class="logo">
                <img src="http://localhost/clae/Imagenes/Logo_Uadeo.png" alt="Logo de la Uadeo">
                <h1>Sistema de Citas UAdeO</h1>
            </div>
        </div>

        <div class="caja">
            <div class="login">
                <h2>Iniciar Sesión</h2>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <div>
                    <div>
                        <input type="text" name="username" placeholder="Usuario" required>
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Contraseña" required>
                    </div>
                </div>
                <div class="boton">
                    <button type="submit">Iniciar Sesión</button>
                </div>
            </div>

        </div>

    </form>
</body>

</html>