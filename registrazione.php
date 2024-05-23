<?php
// Connessione al database
$host = '127.0.0.1';
$db = 'localhost'; 
$user = 'root'; 
$pass = '';  
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

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $tipo_utente = $_POST['tipo_utente'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM utenti WHERE email = ?');
    $stmt->execute([$email]);
    $utente_esistente = $stmt->fetchColumn();

    if ($utente_esistente) {
        $message = "Utente già registrato.";
    } else {
        $stmt = $pdo->prepare('INSERT INTO utenti (nome, cognome, email, password, tipo_utente) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$nome, $cognome, $email, $password, $tipo_utente]);
        $message = "Registrazione avvenuta con successo!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/prova.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .navbar {
            width: 100%;
            background-color: white !important;
            position: absolute;
            top: 0;
        }
        .navbar a, .navbar button {
            color: black !important;
        }
        .navbar button {
            background-color: white;
            border: 1px solid black;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .navbar button:hover {
            background-color: lightgray;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn nav-link" onclick="goToHome()">Torna alla Home</button>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>
    <div class="container">
        <h2>Registrazione Utente</h2>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-primary" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cognome">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="tipo_utente">Tipo Utente</label>
                <select class="form-control" id="tipo_utente" name="tipo_utente" required>
                    <option value="collaboratore">Collaboratore</option>
                    <option value="cliente">Cliente</option>
                    <option value="artista">Artista</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrati</button>
        </form>
    </div>
    <br><br>

    <script>
        function goToHome() {
            window.location.href = 'index.php';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
