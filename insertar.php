<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "INSERT INTO tu_tabla (nombre, email) VALUES ('$nombre', '$email')"; 
    if ($conn->query($sql) === TRUE) {
        header('Location: interactuar_bd.php'); 
    } else {
        echo "Error al insertar: " . $conn->error;
    }
}
?>
