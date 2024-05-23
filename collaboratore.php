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

// Funzione per eliminare una chiamata cameriere
function eliminaCallCameriere($id, $pdo) {
    $stmt = $pdo->prepare('DELETE FROM call_cameriere WHERE id_call = ?');
    $stmt->execute([$id]);
}

// Funzione per eliminare una prenotazione
function eliminaPrenotazione($id, $pdo) {
    $stmt = $pdo->prepare('DELETE FROM prenotazioni WHERE id_prenotazione = ?');
    $stmt->execute([$id]);
}

// Gestione delle richieste di eliminazione
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] == 'elimina_call') {
        eliminaCallCameriere($id, $pdo);
    } elseif ($_GET['action'] == 'elimina_prenotazione') {
        eliminaPrenotazione($id, $pdo);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Recupera tutte le chiamate cameriere
$stmtCallCameriere = $pdo->query('SELECT * FROM call_cameriere');
$callCameriere = $stmtCallCameriere->fetchAll();

// Recupera tutte le prenotazioni
$stmtPrenotazioni = $pdo->query('SELECT * FROM prenotazioni');
$prenotazioni = $stmtPrenotazioni->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaboratore</title>
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
        }
        .navbar button:hover {
            background-color: lightgray;
        }
        .navbar-nav .nav-item + .nav-item {
            margin-left: 15px;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
            overflow-x: auto;
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
                    <button class="btn nav-link" onclick="goToHome()">Torna alla Home</button>
                </li>
                <li class="nav-item">
                    <button class="btn nav-link" onclick="logout()">Logout</button>
                </li>
                <li class="nav-item">
                    <button class="btn nav-link" onclick="goToCandidature()">Candidature</button>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>
    <div class="container">
        <h2>Chiamate Cameriere</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tavolo</th>
                        <th>Ora</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($callCameriere as $call): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($call['id_call']); ?></td>
                            <td><?php echo htmlspecialchars($call['tavolo']); ?></td>
                            <td><?php echo htmlspecialchars($call['ora']); ?></td>
                            <td>
                                <button class="btn btn-delete" onclick="eliminaCallCameriere(<?php echo $call['id_call']; ?>)">Elimina</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2>Prenotazioni</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Ora</th>
                        <th>Tavolo</th>
                        <th>Bottiglie</th>
                        <th>Email</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prenotazioni as $prenotazione): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prenotazione['id_prenotazione']); ?></td>
                            <td><?php echo htmlspecialchars($prenotazione['data']); ?></td>
                            <td><?php echo htmlspecialchars($prenotazione['ora']); ?></td>
                            <td><?php echo htmlspecialchars($prenotazione['tavolo']); ?></td>
                            <td><?php echo htmlspecialchars($prenotazione['bottiglie']); ?></td>
                            <td><?php echo htmlspecialchars($prenotazione['email']); ?></td>
                            <td>
                                <button class="btn btn-delete" onclick="eliminaPrenotazione(<?php echo $prenotazione['id_prenotazione']; ?>)">Elimina</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br><br>

    <script>
        function eliminaCallCameriere(id) {
            if (confirm('Sei sicuro di voler eliminare questa chiamata cameriere?')) {
                window.location.href = '?action=elimina_call&id=' + id;
            }
        }

        function eliminaPrenotazione(id) {
            if (confirm('Sei sicuro di voler eliminare questa prenotazione?')) {
                window.location.href = '?action=elimina_prenotazione&id=' + id;
            }
        }

        function goToHome() {
            window.location.href = 'index.php';
        }

        function logout() {
            window.location.href = 'accesso_collaboratore.php';
        }

        function goToCandidature() {
            window.location.href = 'visualizza_candidature.php';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
