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
    <link rel="stylesheet" href="styles.css">
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
