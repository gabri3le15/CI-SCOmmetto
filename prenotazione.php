<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione</title>
    <link rel="stylesheet" href="css/style_prenotazione.css">
</head>
<style>
    body {
        background-image: url('images/prova.jpg');
    }
    .navbar {
        background-color: blue;
        overflow: hidden;
    }
    .navbar a {
        float: right;
        display: block;
        color: white;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
    }
    .navbar a:hover {
        background-color: darkblue;
    }
</style>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <br><br>
        <img src="images/mappa-tavoli.png" alt="Mappa dei tavoli">
        <br><br>
        <a href="#" class="prenota-button" id="prenotaBtn">Prenota</a>
        <br><br>
    </div>

    <!-- Form di prenotazione - Fase 1 -->
    <div id="fase1" class="form-container" style="display: none;">
        <h2>Data di prenotazione</h2>
        <label for="dataPrenotazione">Data della prenotazione:</label><br>
        <input type="date" id="dataPrenotazione" name="dataPrenotazione" required><br><br>
        <button type="button" class="continuaBtn" id="continuaDataBtn">Continua</button>
    </div>

    <!-- Form di prenotazione - Fase 2 -->
    <div id="fase2" class="form-container" style="display: none;">
        <h2>Modulo di prenotazione - Fase 2</h2>
        <label for="tavolo">Seleziona un tavolo:</label><br>
        <select id="tavolo" name="tavolo" required>
            <?php
                $conn = new mysqli('localhost', 'root', '', 'localhost');

                if ($conn->connect_error) {
                    die("Connessione fallita: " . $conn->connect_error);
                }

                $sql = "SELECT id_tavolo, nome_tavolo, prezzo FROM tavoli";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["nome_tavolo"]."'>".$row["nome_tavolo"]." - ".$row["prezzo"]." euro</option>";
                    }
                } else {
                    echo "<option value=''>Nessun tavolo disponibile</option>";
                }

                $conn->close();
            ?>
        </select><br><br>
        <button type="button" class="continuaBtn" id="continuaTavoloBtn">Continua</button>
    </div>

    <!-- Form di prenotazione - Fase 3 -->
    <div id="fase3" class="form-container" style="display: none;">
        <h2>Modulo di prenotazione - Fase 3</h2>
        <h3>Seleziona una bottiglia:</h3>
        <table>
            <thead>
                <tr>
                    <th>Nome Bottiglia</th>
                    <th>Prezzo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $conn = new mysqli('localhost', 'root', '', 'localhost');

                    if ($conn->connect_error) {
                        die("Connessione fallita: " . $conn->connect_error);
                    }

                    $sql = "SELECT nome_bottiglia, prezzo FROM bottiglie";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["nome_bottiglia"]."</td>";
                            echo "<td>".$row["prezzo"]." euro</td>";
                            echo "<td><button type='button' class='aggiungiBtn' data-nome='".$row["nome_bottiglia"]."' data-prezzo='".$row["prezzo"]."'>Aggiungi</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nessuna bottiglia disponibile</td></tr>";
                    }

                    $conn->close();
                ?>
            </tbody>
        </table>
        <button type="button" class="continuaBtn" id="continuaBottigliaBtn">Continua</button>
    </div>

    <!-- Form di prenotazione - Fase 4 -->
    <div id="fase4" class="form-container" style="display: none;">
        <h2>Modulo di prenotazione - Fase 4</h2>
        <label for="email">Indirizzo Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="orario">Orario della prenotazione:</label><br>
        <input type="time" id="orario" name="orario" required><br><br>
        <button type="button" class="continuaBtn" id="confermaPrenotazioneBtn">Continua</button>
    </div>

    <!-- Form di prenotazione - Fase 5 -->
    <div id="fase5" class="form-container" style="display: none;">
        <h2>Riepilogo della prenotazione - Fase 5</h2>
        <p>Data della prenotazione: <span id="prenotazioneData"></span> </p>
        <p>Tavolo selezionato: <span id="tavoloSelezionato"></span> </p>
        <p>Email: <span id="emailPrenotazione"></span> </p>
        <p>Orario della prenotazione: <span id="orarioPrenotazione"></span></p>
        <h3>Bottiglie nel carrello:</h3>
        <ul id="carrello">
            <!-- Le bottiglie nel carrello verranno aggiunte dinamicamente tramite JavaScript -->
        </ul>
        <button type="button" class="confermaPrenotazioneBtn" id="confermaPrenotazioneBtnFinal">Conferma Prenotazione</button>
        <button type="button" class="continuaBtn" id="modificaPrenotazioneBtn">Modifica Prenotazione</button>
    </div>

    <script>
        var carrello = [];

        document.getElementById('prenotaBtn').addEventListener('click', function() {
            document.getElementById('fase1').style.display = 'block';
        });

        document.getElementById('continuaDataBtn').addEventListener('click', function() {
            document.getElementById('fase1').style.display = 'none';
            document.getElementById('fase2').style.display = 'block';
        });

        document.getElementById('continuaTavoloBtn').addEventListener('click', function() {
            document.getElementById('fase2').style.display = 'none';
            document.getElementById('fase3').style.display = 'block';
        });

        document.getElementById('continuaBottigliaBtn').addEventListener('click', function() {
            document.getElementById('fase3').style.display = 'none';
            document.getElementById('fase4').style.display = 'block';
        });

        document.getElementById('confermaPrenotazioneBtn').addEventListener('click', function() {
            document.getElementById('fase4').style.display = 'none';
            document.getElementById('fase5').style.display = 'block';

            var dataPrenotazione = document.getElementById('dataPrenotazione').value;
            var tavoloSelezionato = document.getElementById('tavolo').selectedOptions[0].text;
            var email = document.getElementById('email').value;
            var orario = document.getElementById('orario').value;

            document.getElementById('prenotazioneData').textContent = dataPrenotazione;
            document.getElementById('tavoloSelezionato').textContent = tavoloSelezionato;
            document.getElementById('emailPrenotazione').textContent = email;
            document.getElementById('orarioPrenotazione').textContent = orario;

            var carrelloElement = document.getElementById('carrello');
            carrelloElement.innerHTML = '';
            carrello.forEach(function(item) {
                var li = document.createElement('li');
                li.textContent = item.nome + ' - ' + item.prezzo + ' euro';
                carrelloElement.appendChild(li);
            });
        });

        document.getElementById('confermaPrenotazioneBtnFinal').addEventListener('click', function() {
            var dataPrenotazione = document.getElementById('dataPrenotazione').value;
            var tavoloId = document.getElementById('tavolo').value;
            var email = document.getElementById('email').value;
            var orario = document.getElementById('orario').value;

            var carrelloDati = JSON.stringify(carrello);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "salva_prenotazione.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("Prenotazione confermata!");
                    location.reload();
                } else if (xhr.readyState == 4) {
                    alert("Errore nella conferma della prenotazione. Per favore riprova.");
                }
            };
            xhr.send("dataPrenotazione=" + encodeURIComponent(dataPrenotazione) + 
                     "&tavoloId=" + encodeURIComponent(tavoloId) + 
                     "&email=" + encodeURIComponent(email) + 
                     "&orario=" + encodeURIComponent(orario) + 
                     "&carrello=" + encodeURIComponent(carrelloDati));
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('aggiungiBtn')) {
                var nome = event.target.getAttribute('data-nome');
                var prezzo = event.target.getAttribute('data-prezzo');
                carrello.push({nome: nome, prezzo: prezzo});
                alert('Bottiglia aggiunta al carrello: ' + nome);
            }
        });

        document.getElementById('modificaPrenotazioneBtn').addEventListener('click', function() {
            document.getElementById('fase5').style.display = 'none';
            document.getElementById('fase1').style.display = 'block';
        });
    </script>
</body>
</html>
