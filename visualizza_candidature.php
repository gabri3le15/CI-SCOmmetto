<?php
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

// Recupera tutte le candidature
$stmtCandidature = $pdo->query('SELECT * FROM candidature');
$candidature = $stmtCandidature->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaboratore - Candidature</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/coll.jpg');
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
            margin: 5px;
        }
        .navbar button:hover {
            background-color: lightgray;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Collaboratore</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn nav-link" onclick="goToCollaboratore()">Indietro</button>
                </li>
                <li class="nav-item">
                    <button class="btn nav-link" onclick="goToHome()">Torna alla Home</button>
                </li>
                <li class="nav-item">
                    <button class="btn nav-link" onclick="logout()">Logout</button>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>
    <div class="container">
        <h2>Candidature</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Data di Nascita</th>
                        <th>Email</th>
                        <th>Numero di Telefono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidature as $candidatura): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($candidatura['id_candidatura']); ?></td>
                            <td><?php echo htmlspecialchars($candidatura['nome']); ?></td>
                            <td><?php echo htmlspecialchars($candidatura['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($candidatura['data_nascita']); ?></td>
                            <td><?php echo htmlspecialchars($candidatura['email']); ?></td>
                            <td><?php echo htmlspecialchars($candidatura['numero_telefono']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br><br>

    <script>
        function goToHome() {
            window.location.href = 'index.php';
        }

        function logout() {
            window.location.href = 'accesso_collaboratore.php';
        }

        function goToCollaboratore() {
            window.location.href = 'collaboratore.php';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
