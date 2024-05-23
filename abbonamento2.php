<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/prova.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .navbar {
            width: 100%;
            background-color: blue !important;
            position: absolute;
            top: 0;
        }
        .navbar a {
            color: white !important;
        }
        .navbar button {
            background-color: blue;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .navbar button:hover {
            background-color: blue;
        }
        .navbar a.btn-link {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
            text-decoration: none;
        }
        .navbar a.btn-link:hover {
            background-color: darkblue;
        }
        .container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            top: 70px; /* Sposta il container sotto la navbar */
        }
        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-row {
            margin-bottom: 20px;
        }
        .form-row label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
            text-align: left; /* Allinea il testo delle etichette a sinistra */
            width: 100%;
        }
        .form-row input {
            width: 100%; /* Larghezza fissa per centrare */
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            display: block;
        }
        .form-row input:focus {
            outline: none;
            border-color: #80bdff;
        }
        .button {
            width: 100%;
            padding: 10px 20px;
            background-color: blue;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .button:hover {
            background-color: darkblue;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn btn-link nav-link text-white" onclick="goToHome()">Torna alla Home</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Inserisci i dati della carta</h2>
        <form action="" method="POST" id="payment-form">
            <div class="form-row">
                <label for="card-number">Numero della Carta</label>
                <input type="text" id="card-number" name="card_number" required>
                <div class="error" id="card-number-error"></div>
            </div>
            <div class="form-row">
                <label for="card-expiry">Data di Scadenza (MM/YY)</label>
                <input type="text" id="card-expiry" name="card_expiry" required>
                <div class="error" id="card-expiry-error"></div>
            </div>
            <div class="form-row">
                <label for="card-cvc">CVC</label>
                <input type="text" id="card-cvc" name="card_cvc" required>
                <div class="error" id="card-cvc-error"></div>
            </div>
            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div class="error" id="email-error"></div>
            </div>
            <button type="submit" class="button" name="pay">Paga</button>
        </form>

        <?php
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'localhost';

        // Connessione al database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Controlla la connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
            $card_number = htmlspecialchars($_POST['card_number']);
            $card_expiry = htmlspecialchars($_POST['card_expiry']);
            $card_cvc = htmlspecialchars($_POST['card_cvc']);
            $email = htmlspecialchars($_POST['email']);

            // Mostra i dati della carta (solo a scopo dimostrativo, non fare in produzione)
            echo "<div class='message'>";
            echo "<h3>Dati della carta ricevuti:</h3>";
            echo "<p>Numero della Carta: " . $card_number . "</p>";
            echo "<p>Data di Scadenza: " . $card_expiry . "</p>";
            echo "<p>CVC: " . $card_cvc . "</p>";
            echo "<p>Email: " . $email . "</p>";
            echo "</div>";

            // Query per aggiornare l'abbonamento
            $sql = "UPDATE utenti SET abbonamento = 2 WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);

            echo "<div class='message'>";
            if ($stmt->execute()) {
                echo "<p>Abbonamento aggiornato con successo. Verrai reindirizzato alla home page in 5 secondi...</p>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 5000);
                      </script>";
            } else {
                echo "<p>Errore nell'aggiornamento dell'abbonamento: " . $stmt->error . "</p>";
            }
            echo "</div>";

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>

    <script>
        function goToHome() {
            window.location.href = 'index.php';
        }

        document.getElementById('payment-form').addEventListener('submit', function(event) {
            var valid = true;

            // Validazione numero della carta
            var cardNumber = document.getElementById('card-number').value;
            var cardNumberError = document.getElementById('card-number-error');
            if (!/^\d{16}$/.test(cardNumber)) {
                cardNumberError.textContent = 'Numero di carta non valido.';
                valid = false;
            } else {
                cardNumberError.textContent = '';
            }

            // Validazione data di scadenza
            var cardExpiry = document.getElementById('card-expiry').value;
            var cardExpiryError = document.getElementById('card-expiry-error');
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry)) {
                cardExpiryError.textContent = 'Data di scadenza non valida.';
                valid = false;
            } else {
                cardExpiryError.textContent = '';
            }

            // Validazione CVC
            var cardCvc = document.getElementById('card-cvc').value;
            var cardCvcError = document.getElementById('card-cvc-error');
            if (!/^\d{3}$/.test(cardCvc)) { cardCvcError.textContent = 'CVC non valido.';
                valid = false;
            } else {
                cardCvcError.textContent = '';
            }

            // Validazione email
            var email = document.getElementById('email').value;
            var emailError = document.getElementById('email-error');
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                emailError.textContent = 'Email non valida.';
                valid = false;
            } else {
                emailError.textContent = '';
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
