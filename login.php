<?php

if ($_SERVER['POST']) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $output = shell_exec("sudo ssh $username@$password");
    echo "User $username logged in";

    header("Location: /home/$username");
    exit;
}
?>

<html>

<head>
    <title>Formulaire de connexion</title>
</head>
    <body>
        <form action="login.php" method="post">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" />

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" />

            <input type="submit" value="Se connecter" />
        </form>
    </body>
</html>