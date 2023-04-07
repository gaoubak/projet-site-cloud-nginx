<?php
function authenticate_user($connect, $username, $password) {
    $stmt = $connect->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        if (authenticate_user($connect, $username, $password)) {
            // User authenticated, set session variable and redirect to protected page
            $_SESSION['username'] = $username;
            header('Location: protected.php');
            exit();
        } else {
            // Invalid username or password, display error message
            $error_message = "Invalid username or password";
        }
    } else {
        // Invalid CSRF token, display error message
        $error_message = "Invalid CSRF token";
    }
}

// Generate new CSRF token
$_SESSION['token'] = bin2hex(random_bytes(32));

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
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

        </form>
    </body>
</html>
