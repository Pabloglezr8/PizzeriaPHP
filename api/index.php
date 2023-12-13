<?php 
    // Mostrar todos los errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Incluir el archivo para la conexión a la base de datos
    include("./connectDB.php");

    // Iniciar la sesión
    session_start();

    // Variable para mensajes
    $message = null;

    // Verificar si se ha enviado un formulario por el método POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Obtener el nombre de usuario y la contraseña del formulario
        $username = $_POST["username"];
        $password = $_POST["password"];
        $rol = null;

        // Verificar el tipo de usuario (admin o no admin)
        if($username == "admin"){
            $rol = 0; // Si el nombre de usuario es "admin", asignar rol 0
        }else{
            $rol = 1; // Si no, asignar rol 1
        }

        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if($conn){
            // Preparar la consulta para insertar un nuevo usuario en la base de datos
            $query = "INSERT INTO usuarios (user, password, rol) VALUES (:username, :password, :rol)";
            $statement = $conn->prepare($query);

            $statement->bindParam(":username", $username);
            $statement->bindParam(":password", $password);
            $statement->bindParam(":rol", $rol);

            // Ejecutar la consulta para insertar el nuevo usuario
            if($statement->execute() && !empty($username) && !empty($password)){
                $message = "Usuario registrado correctamente";
                echo "<p class='message color-message-success'>" . $message . "</p>";
                header("Refresh: 3; url=./login.php");
                exit();
            }else{
                $message = "Error al registrar al usuario";
                echo "<p class='message color-message-error'>" . $message . "</p>";
                header("Refresh: 3; url=" . $_SERVER['PHP_SELF']); 
            }
        }else{
            echo "Error al conectar a la Base de Datos";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="body-index">
    <!-- Formulario para el registro de usuarios -->
    <form method="post" class="enter">
        <label for="username">Nombre de usuario</label>
        <input type="text" name="username" id="username">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password">
        <div class='btn-container'>
            <button class='btn insertar' type='submit' name='<?= ($pizzaToEdit ? "update" : "insert") ?>' id='insert-btn'><img src="../assets/img/pizzaButton.png" alt="Pizza Button" id="pizzaButton"></img> Registrarse</button>
        </div>
    </form>

    <img src="../assets/img/pizzahouse.png" alt="Pizza logo" class="logo-index">
    
    <!-- Enlace para iniciar sesión -->
    <a href="./login.php" class="a-login">Iniciar sesión</a>
</body>
</html>
