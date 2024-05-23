<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordinazione</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/prova.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* Layout verticale */
            align-items: center;
            height: 100vh;
        }
        .navbar {
            width: 100%;
            background-color: blue;
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            position: fixed; /* Fissa la navbar in alto */
            top: 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }
        .navbar a:hover {
            background-color: darkblue;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            margin-top: 70px; /* Sposta il container sotto la navbar */
            min-height: 600px; /* Aumenta l'altezza del contenitore */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Centra il contenuto all'interno del container */
        }
        .container h1 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        img {
            max-width: 300px;
            height: auto;
            margin-bottom: 20px;
        }
        #waiterForm {
            display: none;
        }
        select, button {
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }
        button {
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: skyblue;
        }
        .message {
            margin-top: 20px;
            color: green;
        }
        .error {
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Torna alla Home</a>
    </div>
    <br>
    <div class="container">
        <h1>Drink List</h1>
        
        <?php
        $qrCodeImagePath = 'images/qr.png';
        if (file_exists($qrCodeImagePath)) {
            echo '<img src="' . $qrCodeImagePath . '" alt="QR Code">';
        } else {
            echo '<div class="error">L\'immagine QR code non è disponibile.</div>';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "localhost";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connessione al database fallita: " . $conn->connect_error);
            }

            $tavolo = $_POST['table'];
            $ora = date("Y-m-d H:i:s");
            $data = date("Y-m-d"); // Data attuale

            $sql = "INSERT INTO call_cameriere (tavolo, ora, data) VALUES ('$tavolo', '$ora', '$data')";

            if ($conn->query($sql) === TRUE) {
                echo '<div class="message">Chiamata cameriere salvata con successo!</div>';
            } else {
                echo '<div class="error">Errore durante il salvataggio della chiamata cameriere: ' . $conn->error . '</div>';
            }

            $conn->close();
        }
        ?>

        <br>
        <button onclick="showWaiterForm()">Chiama Cameriere</button>

        <form id="waiterForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="table">Seleziona il tavolo:</label>
            <select id="table" name="table">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "localhost";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connessione al database fallita: " . $conn->connect_error);
                }

                $sql = "SELECT id_tavolo, nome_tavolo FROM tavoli";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["nome_tavolo"] . '">' . $row["nome_tavolo"] . '</option>';
                    }
                } else {
                    echo '<option value="">Nessun tavolo disponibile</option>';
                }

                $conn->close();
                ?>
            </select>
            <button type="submit">Conferma</button>
        </form>
        
        <script>
            function showWaiterForm() {
                document.getElementById("waiterForm").style.display = "block";
            }
            function goToHome() {
                window.location.href = 'index.php';
            }
        </script>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
