<?php
session_start();

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

// Funzione per eseguire l'accesso
function login($email, $password, $pdo) {
    $stmt = $pdo->prepare('SELECT * FROM utenti WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && md5($password) === $user['password']) {
        $_SESSION['user'] = $user;
        header('Location: '.$_SERVER['PHP_SELF']);  // Redireziona alla stessa pagina dopo il login
        exit();
    } else {
        echo 'Email o password errati!';
    }
}

// Funzione per registrare un nuovo utente
function register($nome, $cognome, $email, $password, $tipo_utente, $pdo) {
    // Controlla se l'email è già registrata
    $stmt = $pdo->prepare('SELECT * FROM utenti WHERE email = ?');
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();
    if ($existing_user) {
        echo 'Questo indirizzo email è già registrato!';
        return;
    }

    // Registra il nuovo utente
    $stmt = $pdo->prepare('INSERT INTO utenti (nome, cognome, email, password, tipo_utente) VALUES (?, ?, ?, ?, ?)');
    $hashed_password = md5($password);
    $stmt->execute([$nome, $cognome, $email, $hashed_password, $tipo_utente]);
    echo 'Registrazione avvenuta con successo! Puoi effettuare il login.';
}

// Funzione per recuperare le prenotazioni dell'utente
function getPrenotazioni($email, $pdo) {
    $stmt = $pdo->prepare('SELECT pt.*, t.nome_tavolo FROM prenotazioni pt
                           JOIN tavoli t ON pt.tavolo = t.id_tavolo
                           WHERE pt.email = ?');
    $stmt->execute([$email]);
    return $stmt->fetchAll();
}

// Funzione per recuperare i tavoli
function getTavoli($pdo) {
    $stmt = $pdo->query('SELECT id_tavolo, nome_tavolo FROM tavoli');
    return $stmt->fetchAll();
}

// Funzione per modificare una prenotazione
function modificaPrenotazione($prenotazione_id, $data, $ora, $tavolo, $pdo) {
    $stmt = $pdo->prepare('UPDATE prenotazioni SET data = ?, ora = ?, tavolo = ? WHERE id_prenotazione = ?');
    $stmt->execute([$data, $ora, $tavolo, $prenotazione_id]);
    echo '<p class="success-message">Prenotazione aggiornata con successo!</p>';
}

// Funzione per eseguire il logout
function logout() {
    session_unset();
    session_destroy();
    header('Location: '.$_SERVER['PHP_SELF']);  // Redireziona alla stessa pagina dopo il logout
    exit();
}

// Controllo dei dati inviati tramite POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Gestione login
        $email = $_POST['email'];
        $password = $_POST['password'];
        login($email, $password, $pdo);
    } elseif (isset($_POST['register'])) {
        // Gestione registrazione
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $tipo_utente = 'cliente';  // Puoi modificare questo valore in base al tuo sistema di ruoli
        register($nome, $cognome, $email, $password, $tipo_utente, $pdo);
    } elseif (isset($_POST['modifica_prenotazione'])) {
        // Gestione modifica prenotazione
        if (isset($_POST['prenotazione_id'], $_POST['data'], $_POST['ora'], $_POST['tavolo'])) {
            $prenotazione_id = $_POST['prenotazione_id'];
            $data = $_POST['data'];
            $ora = $_POST['ora'];
            $tavolo = $_POST['tavolo'];
            modificaPrenotazione($prenotazione_id, $data, $ora, $tavolo, $pdo);
        } else {
            echo '<p class="error-message">Errore nella modifica della prenotazione. Per favore, riprova.</p>';
        }
    } elseif (isset($_POST['logout'])) {
        // Gestione logout
        logout();
    }
}

// Recupera le prenotazioni per l'utente loggato
$prenotazioni = [];
if (isset($_SESSION['user'])) {
    $prenotazioni = getPrenotazioni($_SESSION['user']['email'], $pdo);
    $tavoli = getTavoli($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza prenotazioni</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS */
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/prova.jpg');
            background-size: cover;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #000;
            width: 400px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #bf3d42;
        }

        input[type="submit"], button {
            background-color: blue;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover, button:hover {
            background-color: darkblue;
        }

        .prenotazioni-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #000;
            margin-top: 20px;
            width: 100%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-modifica {
            display: none;
            margin-top: 10px;
        }

        .form-modifica form {
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: blue !important;
        }

        .navbar a {
            color: white !important;
        }

        .navbar button {
            background-color: blue;
            border: none;
            color: white;
        }

        .navbar .btn {
            margin: 0;
            padding: 0;
            border: none;
            background-color: transparent;
        }

        .navbar button:hover {
            background-color: darkblue;
        }

        .success-message {
            font-size: 1.5em;
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .error-message {
            font-size: 1.5em;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        #logoutButton {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <a class="navbar-brand" href="#">Prenotazioni</a>
        <a class="navbar-brand" href="index.php">Torna alla Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <span class="navbar-text">
                Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>
            </span>
        <?php endif; ?>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Tabella prenotazioni -->
            <div class="prenotazioni-container">
                <h2>Le tue prenotazioni</h2>
                <?php if (!empty($prenotazioni)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ora</th>
                                <th>Tavolo</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prenotazioni as $prenotazione): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prenotazione['data']); ?></td>
                                    <td><?php echo htmlspecialchars($prenotazione['ora']); ?></td>
                                    <td><?php echo htmlspecialchars($prenotazione['nome_tavolo']); ?></td>
                                    <td>
                                        <button onclick="showModificaForm(<?php echo $prenotazione['id_prenotazione']; ?>)">Modifica</button>
                                    </td>
                                </tr>
                                <tr class="form-modifica" id="form-modifica-<?php echo $prenotazione['id_prenotazione']; ?>">
                                    <td colspan="4">
                                        <form method="POST">
                                            <input type="hidden" name="prenotazione_id" value="<?php echo $prenotazione['id_prenotazione']; ?>">
                                            <label for="data-<?php echo $prenotazione['id_prenotazione']; ?>">Data:</label>
                                            <input type="date" name="data" id="data-<?php echo $prenotazione['id_prenotazione']; ?>" value="<?php echo $prenotazione['data']; ?>" required>
                                            <label for="ora-<?php echo $prenotazione['id_prenotazione']; ?>">Ora:</label>
                                            <input type="time" name="ora" id="ora-<?php echo $prenotazione['id_prenotazione']; ?>" value="<?php echo $prenotazione['ora']; ?>" required>
                                            <label for="tavolo-<?php echo $prenotazione['id_prenotazione']; ?>">Tavolo:</label>
                                            <select name="tavolo" id="tavolo-<?php echo $prenotazione['id_prenotazione']; ?>" required>
                                                <?php foreach ($tavoli as $tavolo): ?>
                                                    <option value="<?php echo $tavolo['id_tavolo']; ?>" <?php if ($tavolo['id_tavolo'] == $prenotazione['tavolo']) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($tavolo['nome_tavolo']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="submit" name="modifica_prenotazione" value="Modifica prenotazione">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Non hai prenotazioni.</p>
                <?php endif; ?>
            </div>

            <!-- Pulsante di logout -->
            <div id="logoutButton">
                <form method="POST">
                    <input type="submit" name="logout" value="Logout">
                </form>
            </div>
        <?php else: ?>
            <!-- Modulo di login -->
            <form method="POST" id="loginForm">
                <h2>Login</h2>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="login" value="Login">
            </form>

            <!-- Pulsante per mostrare il modulo di registrazione -->
            <button id="showRegisterFormButton">Non hai un account? Registrati</button>

            <!-- Modulo di registrazione nascosto inizialmente -->
            <form method="POST" id="registerForm" style="display: none;">
                <h2>Registrazione</h2>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <label for="cognome">Cognome:</label>
                <input type="text" id="cognome" name="cognome" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="register" value="Registrati">
            </form>
        <?php endif; ?>
    </div>

    <script>
        function showModificaForm(prenotazioneId) {
            var form = document.getElementById('form-modifica-' + prenotazioneId);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }

        document.getElementById('showRegisterFormButton').addEventListener('click', function() {
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('showRegisterFormButton').style.display = 'none';
        });
    </script>
</body>
</html>
