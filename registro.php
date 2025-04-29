<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Usuario = $_POST['Usuario'];
    $Contrase = password_hash($_POST['Contrase'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (Usuario, Contrase) VALUES (?, ?)");
    $stmt->bind_param("ss", $Usuario, $Contrase);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro e Inicio de Sesión</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Quicksand";
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background: #000;
            width: 100%;
            overflow: hidden;
        }

        h2 {
            font-size: 2em;
            color: #fff;
            margin-bottom: 20px;
        }

        .ring {
            position: relative;
            width: 500px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ring i {
            position: absolute;
            inset: 0;
            border: 2px solid #fff;
            transition: 0.5s;
        }

        .ring i:nth-child(1) {
            border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
            animation: animate 6s linear infinite;
        }

        .ring i:nth-child(2) {
            border-radius: 41% 44% 56% 59% / 38% 62% 63% 37%;
            animation: animate 4s linear infinite;
        }

        .ring i:nth-child(3) {
            border-radius: 41% 44% 56% 59% / 38% 62% 63% 37%;
            animation: animate2 10s linear infinite;
        }

        .ring:hover i {
            border: 6px solid var(--clr);
            filter: drop-shadow(0 0 20px var(--clr));
        }

        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes animate2 {
            0% {
                transform: rotate(360deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }

        .form-container {
            position: absolute;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .form-container .inputBx {
            position: relative;
            width: 80%;
            margin-top: 20px;
        }

        .form-container .inputBx input {
            width: 100%;
            padding: 12px 20px;
            background: transparent;
            border: 2px solid #fff;
            border-radius: 40px;
            font-size: 1.2em;
            color: #fff;
            outline: none;
        }

        .form-container .inputBx input[type="submit"] {
            background: linear-gradient(45deg, #ff357a, #fff172);
            border: none;
            cursor: pointer;
        }

        .form-container .inputBx input::placeholder {
            color: rgba(255, 255, 255, 0.75);
        }

        .small-link {
            margin-top: 10px;
            font-size: 0.9em;
            color: #fff;
            text-decoration: none;
        }

        .small-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Registro</h2>
    <div class="ring">
        <i style="--clr:#00ff0a;"></i>
        <i style="--clr:#ff0057;"></i>
        <i style="--clr:#fffd44;"></i>
        <div class="form-container">
            <form action="registro.php" method="post">
                <div class="inputBx">
                    <input type="text" name="Usuario" placeholder="Usuario" required>
                </div>
                <div class="inputBx">
                    <input type="password" name="Contrase" placeholder="Contraseña" required>
                </div>
                <div class="inputBx">
                    <input type="submit" value="Registrar">
                </div>
            </form>
            
            <a href="login.php" class="small-link">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>
