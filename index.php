<?php
require_once 'conexion.php';
require_once 'servicios.php';

session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Usuario = isset($_POST['Usuario']) ? trim($_POST['Usuario']) : '';
    $Contrasenia = isset($_POST['Contrasenia']) ? trim($_POST['Contrasenia']) : '';

    if (!empty($Usuario) && !empty($Contrasenia)) {
        $sql = "SELECT Id, Usuario, Contrasenia FROM usuarios WHERE Usuario = '$Usuario'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($Contrasenia === $row['Contrasenia']) {
                $token = generarToken();
                actualizarToken($row['Id'], $token);

                $_SESSION['token'] = $token;
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Nombre de usuario o contraseña incorrectos";
            }
        } else {
            $error_message = "Nombre de usuario o contraseña incorrectos";
        }
    } else {
        $error_message = "Por favor, completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

        <form method="post" action="">
            <label for="Usuario">Usuario:</label>
            <input type="text" name="Usuario" id="Usuario" required>

            <label for="Contrasenia">Contraseña:</label>
            <input type="password" name="Contrasenia" id="Contrasenia" required>

            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
