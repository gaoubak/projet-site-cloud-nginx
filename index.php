<?php
$username = $_POST['username'];
$password = $_POST['password'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        user_create($username, $password);
    }        
}


function user_create($username, $password) {
    $user = shell_exec("sudo useradd -m -p $password $username");
    echo "User $username created with password $password";

    $output = shell_exec("sudo usermod -aG sudo $username");
    echo "User $username added to sudo group";

    $output = shell_exec("sudo chown -R $username:$username /home/$username");
    echo "User $username home directory changed";

    $output = shell_exec("sudo chmod 755 /home/$username");
    echo "User $username home directory permissions changed";
}

?>

<html>
    
<head>
    <title>Formulaire d'inscription</title>
</head>

    <body>
        <form action="index.php" method="post">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" />

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" />

            <input type="submit" value="S'inscrire" />
        </form>
        <a href="login.php">Se connecter</a>
    </body>
</html>
