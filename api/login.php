<?php 
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


        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if($conn){
            // Consultar la base de datos para obtener el usuario por su nombre de usuario
            $query = "SELECT id, user, password, rol FROM usuarios WHERE user = :username";
            $statement = $conn->prepare($query);
            $statement->bindParam(":username", $username);
            $statement->execute(); 

            // Obtener los datos del usuario de la base de datos
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el usuario y si la contraseña es correcta
            if($user && $user["password"]){
                // Establecer las variables de sesión para el usuario
                $_SESSION["id"] = $user["id"];
                $_SESSION["user"] = $username;
                 
                // Redireccionar según el rol del usuario (admin o usuario normal)
                if($user["rol"] == 0){
                    $message = "Inicio de sesión exitoso";
                    echo "<p class='message color-message-success'>" . $message . "</p>";
                    header("Refresh: 3; url=./admin.php"); 

                }else{
                    $message = "Inicio de sesión exitoso";
                    echo "<p class='message color-message-success'>" . $message . "</p>";
                    header("Refresh: 3; url=./user.php");

                }
            }
            // Si no se encontró el usuario, mostrar un mensaje de error
            if(!$user){
                $message = "Error al iniciar sesión";
                echo "<p class='message color-message-error'>" . $message . "</p>";
                header("Refresh: 3; URL=" . $_SERVER['PHP_SELF']);
            } 
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="body-login">
    <!-- Formulario para iniciar sesión -->
    <form method="post" class=enter>
        <label for="username">Nombre de usuario</label>
        <input type="text" name="username" id="username">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password">

        <div class='btn-container'>
            <button class='btn insertar' type='submit' name='<?= ($pizzaToEdit ? "update" : "insert") ?>' id='insert-btn'><img src="../assets/img/pizzaButton.png" alt="Pizza Button" id="pizzaButton"></img> Iniciar Sesión</button>
        </div>
    </form>

    <img src="../assets/img/pizzahouse.png" alt="Pizza logo" class="logo-index">
    
    <!-- Enlace para registrarse -->
    <a href="./index.php" class="a-registrarse">Registrarse</a>
</body>
</html>
