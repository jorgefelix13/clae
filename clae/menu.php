<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Define las opciones del menú para cada rol
$menu_opciones = [
    'administrador' => [
        "Registrar" => "registrar.php",
        "Agendar" => "agendar.php",
        "Ajustes" => "ajustes.php"
    ],
    'recepcion' => [
        "Registrar" => "registrar.php",
        "Agendar" => "agendar.php",
    ],
    'doctor' => [
    ],
    'nutricion' => [
    ],
    'psicologia' => [
    ]
];

// Obtiene el rol del usuario actual
$rol_actual = $_SESSION['role'];
$menu = $menu_opciones[$rol_actual];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <link rel="stylesheet" href="menu.css">
    <style>
       
    </style>
</head>
<body>
    <nav>
        <?php foreach ($menu as $nombre => $link): ?>
            <a href="<?php echo $link; ?>"><?php echo $nombre; ?></a>
        <?php endforeach; ?>
        <a href="inicio.php" style="color: red;">Cerrar sesión</a>
    </nav>
</body>
</html>

