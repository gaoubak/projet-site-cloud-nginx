<?php

if ($_SERVER['POST']) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $output = shell_exec("sudo ssh $username@$password");
    echo "User $username logged in";

    shell_exec("cd /home/$username; sudo touch index.php; echo '<?php phpinfo(); ?>' > index.php");
    echo "User $username index.php created";

    header("Location: /home/$username");
    exit;
}
?>

<html>

<head>
    <title>Formulaire de connexion pour vous permettre d'upload vos fichiers</title>
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