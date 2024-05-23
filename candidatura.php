<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatura DJ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(images/candidatura.jpg);
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
            position: relative;
            z-index: 1;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="submit"],
        input[type="button"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="button"]:focus {
            outline: none;
            border-color: #5e72e4;
        }
        input[type="submit"] {
            background-color: #5e72e4;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #4054b2;
        }
        input[type="button"] {
            background-color: #aaa;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="button"]:hover {
            background-color: #666;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="overlay"></div>
<div class="container">
    <h1>Candidati ora</h1>
    <?php
    // Configurazione del database
    $servername = "localhost";
    $username = "root"; // Modifica con il tuo nome utente del database
    $password = ""; // Modifica con la tua password del database
    $dbname = "localhost";

    // Connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Verifica se il form è stato inviato
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomeCandidato = $_POST['nome'];
        $cognomeCandidato = $_POST['cognome'];
        $dataNascita = $_POST['data_nascita'];
        $email = $_POST['email'];
        $numeroTelefonico = $_POST['numero_telefono'];

        // Controllo sulla data di nascita
        $dataNascitaMinima = date('Y-m-d', strtotime('-18 years'));
        if ($dataNascita > $dataNascitaMinima) {
            echo "<p class='message'>Devi avere almeno 18 anni per candidarti.</p>";
        } else {
            // Controllo se esiste già una candidatura con lo stesso nome, cognome ed email
            $checkQuery = "SELECT * FROM candidature WHERE nome = '$nomeCandidato' AND cognome = '$cognomeCandidato' AND email = '$email'";
            $checkResult = $conn->query($checkQuery);
            if ($checkResult->num_rows > 0) {
                echo "<p class='message'>Hai già inviato una candidatura.</p>";
            } else {
                // Preparazione della query SQL
                $sql = "INSERT INTO candidature (nome, cognome, data_nascita, email, numero_telefono) 
                        VALUES ('$nomeCandidato', '$cognomeCandidato', '$dataNascita', '$email', '$numeroTelefonico')";

                // Esecuzione della query
                if ($conn->query($sql) === TRUE) {
                    echo "<p class='message'>Candidatura inviata con successo!</p>";
                } else {
                    echo "<p class='message'>Errore durante l'invio della candidatura: " . $conn->error . "</p>";
                }
            }
        }
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="text" name="nome" placeholder="Nome" required><br>
        <input type="text" name="cognome" placeholder="Cognome" required><br>
        <input type="date" name="data_nascita" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="tel" name="numero_telefono" placeholder="Numero Telefonico" required><br>
        <input type="submit" value="Invia Candidatura">
    </form>
    <input type="button" value="Torna alla Home" onclick="window.location.href = 'index.php';">
        </div>
    S</body>
</html>
