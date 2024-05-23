<?php
// Dati di connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "localhost";

// Crea connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Recupera i dati dalla richiesta POST
$dataPrenotazione = $_POST['dataPrenotazione'];
$tavoloId = $_POST['tavoloId'];
$email = $_POST['email'];
$orario = $_POST['orario'];
$carrello = json_decode($_POST['carrello'], true);

// Costruisci la stringa delle bottiglie
$bottiglie = implode(', ', array_map(function($item) {
    return $item['nome'] . ' - ' . $item['prezzo'] . ' euro';
}, $carrello));

// Query per inserire i dati della prenotazione
$sql = "INSERT INTO prenotazioni (tavolo, data, ora, bottiglie, email) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $tavoloId, $dataPrenotazione, $orario, $bottiglie, $email);

if ($stmt->execute()) {
    echo "Prenotazione salvata con successo!";
} else {
    echo "Errore nell'inserimento della prenotazione: " . $stmt->error;
}

// Chiudi connessione
$conn->close();
?>
