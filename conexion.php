<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "electroniccomponents";


$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);


if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}
echo "Conexión exitosa";
?>
