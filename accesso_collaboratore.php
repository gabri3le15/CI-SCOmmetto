<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Cripta la password con MD5

    // Connessione al database
    $host = '127.0.0.1';
    $db = 'localhost'; // Sostituisci con il nome del tuo database
    $user = 'root';  // Modifica con il tuo utente DB
    $pass = '';  // Modifica con la tua password DB
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    // Query per verificare le credenziali
    $stmt = $pdo->prepare('SELECT * FROM utenti WHERE email = ? AND password = ?');
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Controllo del tipo di utente
        if ($user['tipo_utente'] === 'collaboratore') {
            // Redirect alla pagina collaboratore.php se l'utente è un collaboratore
            header('Location: collaboratore.php');
            exit();
        } elseif ($user['tipo_utente'] === 'cliente') {
            // Redirect alla pagina index.php con un messaggio se l'utente è un cliente
            $_SESSION['error_message'] = "Qui non puoi accedere.";
            header('Location: index.php');
            exit();
        }
    } else {
        $login_error = "Email o password errati.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Collaboratore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/coll.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #000;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .navbar {
            overflow: hidden;
            background-color: #fff;
            position: fixed;
            top: 0;
            width: 100%;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 2px solid black;
            z-index: 1000;
        }
        .navbar a {
            float: left;
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background: #ddd;
            color: black;
        }
        .container {
            max-width: 400px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            border: 2px solid black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <h2>Login</h2>
        <?php if (isset($login_error)) echo '<div class="error">' . $login_error . '</div>'; ?>
        <form method="post">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
