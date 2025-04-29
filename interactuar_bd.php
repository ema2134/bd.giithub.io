<?php
include 'conexion.php';
session_start();

// Validar si el usuario está logueado
if (!isset($_SESSION['Usuario'])) {
    header('Location: login.php'); // Redirigir si no está logueado
    exit;
}

// Obtener la opción seleccionada desde el menú
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : null;

// Configurar opciones válidas y sus columnas correspondientes
$opciones = [
    'cliente' => [
        'titulo' => 'Clientes',
        'tabla' => 'customers',
        'columnas' => ['id', 'nombre', 'correo', 'telefono', 'direccion']
    ],
    'pedido' => [
        'titulo' => 'Pedidos',
        'tabla' => 'orders',
        'columnas' => ['id', 'orden', 'fecha', 'estado']
    ],
    'detalles_pedido' => [
        'titulo' => 'Detalles de Pedido',
        'tabla' => 'order_details',
        'columnas' => ['id', 'orden', 'producto', 'cantidad', 'precio']
    ],
    'producto' => [
        'titulo' => 'Productos',
        'tabla' => 'products',
        'columnas' => ['id', 'nombre', 'descripcion', 'precio', 'stock']
    ],
    'proveedores' => [
        'titulo' => 'Proveedores',
        'tabla' => 'suppliers',
        'columnas' => ['id', 'nombre', 'apellido', 'correo', 'telefono', 'direccion']
    ]
];

// Verificar si hay una selección válida
$seleccion = isset($opciones[$tabla]) ? $opciones[$tabla] : null;

// Validar si se están ingresando nuevos datos
if ($seleccion && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $columnas = $seleccion['columnas'];
    $valores = array_map(fn($columna) => $_POST[$columna], array_slice($columnas, 1)); // Excluir 'id'

    $sql_insert = "INSERT INTO " . $seleccion['tabla'] . " (" . implode(', ', array_slice($columnas, 1)) . ") VALUES (" . implode(', ', array_fill(0, count($valores), '?')) . ")";

    // Preparar la consulta y validar
    $stmt = $conn->prepare($sql_insert);
    if (!$stmt) {
        die("<p class='error'>Error al preparar la sentencia SQL: " . $conn->error . "</p>");
    }

    // Asociar los valores a la consulta preparada
    $stmt->bind_param(str_repeat('s', count($valores)), ...$valores);

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        echo "<p class='success'>Nuevo registro agregado correctamente.</p>";
    } else {
        echo "<p class='error'>Error al agregar registro: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seleccion ? $seleccion['titulo'] : 'Interacción con la Base de Datos'; ?></title>
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
            flex-direction: column;
            min-height: 100vh;
            background: #000;
            color: #fff;
        }

        .navbar {
            width: 100%;
            background: #111;
            border-bottom: 2px solid #fff;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        

        .navbar h1 {
            color: #fff;
            font-size: 1.5em;
            margin: 0;
        }

        .navbar form {
            display: flex;
            align-items: center;
        }

        .navbar input[type="text"] {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #fff;
            border-radius: 5px;
            background: #222;
            color: #fff;
            outline: none;
            margin-right: 10px;
        }

        .navbar input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .navbar button {
            padding: 10px 15px;
            background: linear-gradient(45deg, #ff357a, #fff172);
            border: none;
            border-radius: 5px;
            color: #000;
            font-size: 1em;
            cursor: pointer;
        }

        .navbar button:hover {
            opacity: 0.9;
        }

        .left-rectangle {
            width: 200px;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            background: #111;
            border-right: 2px solid #fff;
            padding: 20px;
            overflow-y: auto;
        }
        .left-rectangle h2 {
    margin-bottom: 30px; /* Separación de 20px entre el título "Opciones" y los botones */
}

        

        .left-rectangle ul li {
            margin-bottom: 40px; /* Separación de 20px entre los botones */
        }
        
        .left-rectangle ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
            border: 1px solid #fff;
            border-radius: 5px;
            text-align: center;
            background: linear-gradient(45deg, #222, #333);
            transition: all 0.3s ease-in-out;
            font-size: 0.9em;
            max-height: 50px; /* Limitar altura máxima */
            overflow: hidden; /* Manejar contenido excedente */
        }

        .left-rectangle ul li a:hover {
            background: linear-gradient(45deg, #ff357a, #fff172);
            color: #000;
            transform: scale(1.05);
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
        }

        .main-container {
            flex: 1;
            margin-left: 200px;
            padding: 80px 20px 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #fff;
            text-align: center;
            padding: 8px;
        }

        th {
            background: #333;
        }

        td {
            background: #222;
        }

        .form-container {
            margin-top: 20px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        input[type="text"], input[type="number"], input[type="email"] {
            background: #222;
            color: #fff;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        button {
            background: linear-gradient(45deg, #ff357a, #fff172);
            color: #000;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        .success {
            color: #0f0;
            font-size: 1em;
            margin-bottom: 10px;
        }

        .error {
            color: #f00;
            font-size: 1em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <div class="navbar">
        <h1>Gestión de Base de Datos</h1>
        <form action="interactuar_bd.php?tabla=<?php echo $tabla; ?>" method="get">
            <input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
            <input type="text" name="buscar" placeholder="Buscar...">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- Menú lateral -->
    <div class="left-rectangle">
        <h2>Opciones</h2>
        <ul>
            <?php foreach ($opciones as $key => $opcion) {
                echo "<li><a href='interactuar_bd.php?tabla=$key'>" . $opcion['titulo'] . "</a></li>";
            } ?>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-container">
        <?php if ($seleccion): ?>
            <h1><?php echo $seleccion['titulo']; ?></h1>

            <!-- Formulario para agregar datos -->
            <div class="form-container">
                <form action="interactuar_bd.php?tabla=<?php echo $tabla; ?>" method="post">
                    <?php foreach (array_slice($seleccion['columnas'], 1) as $columna): ?>
                        <input type="text" name="<?php echo $columna; ?>" placeholder="<?php echo ucfirst($columna); ?>" required>
                    <?php endforeach; ?>
                    <button type="submit">Agregar <?php echo $seleccion['titulo']; ?></button>
                </form>
            </div>

            <?php
            $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : null;

            if ($buscar) {
                // Realizar la búsqueda
                $columnas = implode(" LIKE ? OR ", $seleccion['columnas']) . " LIKE ?";
                $sql_buscar = "SELECT * FROM " . $seleccion['tabla'] . " WHERE $columnas";

                $stmt = $conn->prepare($sql_buscar);
                $param = '%' . $buscar . '%';
                $params = array_fill(0, count($seleccion['columnas']), $param);

                $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    echo "<table><tr>";
                    foreach ($seleccion['columnas'] as $columna) {
                        echo "<th>" . ucfirst($columna) . "</th>";
                    }
                    echo "</tr>";

                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($seleccion['columnas'] as $columna) {
                            echo "<td>" . htmlspecialchars($fila[$columna]) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No se encontraron resultados para '<strong>" . htmlspecialchars($buscar) . "</strong>'.</p>";
                }

                $stmt->close();
            } else {
                // Mostrar todos los registros por defecto
                $sql = "SELECT * FROM " . $seleccion['tabla'];
                $resultado = $conn->query($sql);

                if ($resultado->num_rows > 0) {
                    echo "<table><tr>";
                    foreach ($seleccion['columnas'] as $columna) {
                        echo "<th>" . ucfirst($columna) . "</th>";
                    }
                    echo "</tr>";

                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($seleccion['columnas'] as $columna) {
                            echo "<td>" . htmlspecialchars($fila[$columna]) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>La tabla está vacía.</p>";
                }
            }
            ?>
        <?php else: ?>
            <h1>Bienvenido</h1>
            <p>Selecciona una opción del menú para interactuar con la base de datos.</p>
        <?php endif; ?>
    </div>
</body>
</html>
