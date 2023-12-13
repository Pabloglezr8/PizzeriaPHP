<?php 
// Incluir el archivo para la conexión a la base de datos
include("connectDB.php");

// Iniciar la sesión
session_start();

// Establecer la conexión a la base de datos
$conn = connectDB();

// Obtener el nombre de usuario de la sesión
$user = $_SESSION['user'];

// Verificar si la conexión a la base de datos fue exitosa
if($conn){
    // Consultar todas las pizzas ordenadas por nombre
    $query = "SELECT * FROM pizzas ORDER BY name";
    $statement = $conn->prepare($query);
    $statement->execute();
    $pizzas = $statement->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <!-- Estructura HTML para mostrar el menú de pizzas -->
    <div class="header">
        <a href="./login.php"><img src="../assets/img/pizzahouse.png" alt="Pizza logo" class="logo"></a>
        <h1 class="title">Pizza House</h1>
        <div></div>
    </div>

    <div class="h2Container">
<div class="line"></div>
<h2 class='heading'>Menú</h2>
<div class="line"></div>
</div>

    <div class="container-menu">
        <?php foreach($pizzas as $pizza): ?>
            <figure class="figure">
                <img src="../assets/img/pizza<?= $pizza['id'] ?>.jpg" alt="pizza image" class="img">
                <figcaption class="custom-caption"><?= $pizza['name'] ?></figcaption>
            </figure>
        <?php endforeach; ?>
    </div>

    <!-- Formulario para seleccionar la cantidad de pizzas -->
    <div class="h2Container">
<div class="line"></div>
<h2 class='heading'>Pedido</h2>
<div class="line"></div>
</div>
    <?php if(count($pizzas) > 0): ?>
        <form method="post" action="orders.php">
            <table class="userTable">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Ingredientes</th>
                    <th>Cantidad</th>
                </tr>
                <?php foreach($pizzas as $pizza): ?>
                    <tr>
                        <td><?= $pizza['name'] ?></td>
                        <td><?= $pizza['price'] ?></td>
                        <td><?= $pizza['ingredients'] ?></td>
                        <td><input type="number" name="quantity[<?= $pizza['id'] ?>]" value="0"></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="orderColumn">
                    <div class='btn-container'>
                        <img src="../assets/img/pizzaButton.png" alt="Pizza Button" id="pizzaButton"></img>
                        <input class="order-btn" type="submit" value="Realizar Pedido">
                    </div>
                    </td>
                </tr>
            </table>
        </form>
    <?php else: ?>
        <p>No se encontraron resultados</p>
    <?php endif;

    // Cerrar la conexión a la base de datos
    $conn = null;
} else {
    echo "Error al conectar a la Base de datos";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $user ?></title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
</body>
</html>
