<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(32));

try {
    $connect = new PDO('mysql:host=localhost:3306;dbname=potato_db', 'Kader', '');
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $stmt = $connect->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }        
}
function user_create($username, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user = shell_exec("sudo useradd -m -p $hashed_password $username");
    echo "User $username created with password $password";

    $output = shell_exec("sudo usermod -aG sudo $username");
    echo "User $username added to sudo group";

    $output = shell_exec("sudo chown -R $username:$username /home/$username");
    echo "User $username home directory changed";

    $output = shell_exec("sudo chmod 755 /home/$username");
    echo "User $username home directory permissions changed";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        user_create($username, $password);
    }        
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
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        </form>
        <a href="login.php">Se connecter</a>
    </body>
</html>
