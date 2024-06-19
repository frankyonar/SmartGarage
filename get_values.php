<?php
include 'config.php';

$queryTemp = "SELECT temperatura FROM temperatura ORDER BY id DESC LIMIT 1";
$queryHum = "SELECT umidita FROM umidita ORDER BY id DESC LIMIT 1";

$resultTemp = mysqli_query($conn, $queryTemp);
$resultHum = mysqli_query($conn, $queryHum);

if ($resultTemp && $resultHum) {
    $temp = mysqli_fetch_assoc($resultTemp)['temperatura'];
    $hum = mysqli_fetch_assoc($resultHum)['umidita'];
    echo json_encode(array("temperatura" => $temp, "umidita" => $hum));
} else {
    echo json_encode(array("error" => "Errore nella lettura dei dati dal database"));
}

mysqli_close($conn);
?>
