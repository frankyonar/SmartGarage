<?php
include 'config.php';

// Ricevi i dati dal ESP32
$temperatura = $_GET['temperatura'];
$umidita = $_GET['umidita'];

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Inserisci i dati nel database
$sql = "INSERT INTO valori_sensori (temperatura, umidita) VALUES ('$temperatura','$umidita')";

if ($conn->query($sql) === TRUE) {
    echo "Dati salvati correttamente";
} else {
    echo "Errore: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
