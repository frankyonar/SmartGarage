<?php
session_start();
include 'config.php';

// Verifica se l'utente è autenticato
if (!isset($_COOKIE['loggedin']) || $_COOKIE['loggedin'] != true) {
    header('Location: login.php');
    exit;
}

// Creazione connessione al database
//$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recupero l'ultimo valore di umidità
$humidity_result = $conn->query("SELECT umidita FROM valori_sensori ORDER BY id DESC LIMIT 1");
$humidity_value = $humidity_result->fetch_assoc()['umidita'];

// Recupero l'ultimo valore di temperatura
$temperature_result = $conn->query("SELECT temperatura FROM valori_sensori ORDER BY id DESC LIMIT 1");
$temperature_value = $temperature_result->fetch_assoc()['temperatura'];

$conn->close();
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en" data-bss-forced-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - Smart Garage</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&display=swap">
    <link rel="stylesheet" href="assets/css/Font%20Awesome%205%20Brands.css">
    <link rel="stylesheet" href="assets/css/Font%20Awesome%205%20Free.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fonts/fontawesome-all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body id="page-top">
<div id="wrapper">
    <nav class="navbar align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0 navbar-dark">
        <div class="container-fluid p-0">
            <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"></div>
                <div class="sidebar-brand-text mx-3"><span>Smart garage</span></div>
            </a>
            <hr class="sidebar-divider my-0">
            <ul class="navbar-nav text-light" id="accordionSidebar">
                <li class="nav-item">
                    <a class="nav-link active" href="logout.php">
                        <button class="btn border-0 rounded-circle" id="sidebarToggle" type="button"></button>
                        <svg></svg>
                    </a>
                </li>
            </ul>
            <div class="text-center d-none d-md-inline"></div>
        </div>
    </nav>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <ul class="navbar-nav flex-nowrap ms-auto">
                        <div class="d-none d-sm-block topbar-divider"></div>
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-item dropdown no-arrow">
                                <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"></a>
                                <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                    <a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                    <a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a>
                                    <a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Dashboard</h3>
                    <button class="btn btn-primary btn-sm d-none d-sm-inline-block" id="chiudiGarage">Chiudi Garage</button>
                    <button class="btn btn-primary btn-sm d-none d-sm-inline-block" id="chiudiLuce">OFF LUCE</button>
                    <button class="btn btn-primary btn-sm d-none d-sm-inline-block" id="ONluce">ON LUCE</button>
                    <button class="btn btn-primary btn-sm d-none d-sm-inline-block" id="ONgarage">Apri Garage</button>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-start-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col me-2">
                                        <div class="text-uppercase fw-bold text-primary text-xs mb-1"><span>Temperatura</span></div>
                                        <div class="fw-bold text-dark h5 mb-0"><span id="valore_temperatura"><?php echo $temperature_value; ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-start-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col me-2">
                                        <div class="text-uppercase fw-bold text-success text-xs mb-1"><span>Umidità</span></div>
                                        <div class="fw-bold text-dark h5 mb-0"><span id="valore_umidita"><?php echo $humidity_value; ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-start-info py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col me-2">
                                        <div class="text-uppercase fw-bold text-info text-xs mb-1"><span>LUCE</span></div>
                                        <div class="row g-0 align-items-center">
                                            <div class="col-auto" id="valore_luce">
                                                <div class="fw-bold text-dark h5 mb-0 me-3"><span>--</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-start-warning py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col me-2">
                                        <div class="text-uppercase fw-bold text-warning text-xs mb-1"><span>PORTA garage</span></div>
                                        <div class="fw-bold text-dark h5 mb-0"><span id="valore_garage">--</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-xl-8">
                        <div class="card shadow mb-4"></div>
                    </div>
                    <div class="col-lg-5 col-xl-4">
                        <div class="card shadow mb-4"></div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright">
                    <span>Copyright © Smart Garage 2024</span>
                </div>
            </div>
        </footer>
    </div>
    <a class="d-inline border rounded scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
</div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bold-and-bright.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/js/theme.js"></script>

<script>
    $(document).ready(function() {
        $('#ONgarage').click(function() {
            $('#valore_garage').text('APERTA');
            $.get('http://192.168.212.38/apri_garage', function(data) {  // Sostituisci con l'IP della tua ESP32
                console.log(data);
            });
        });

        $('#chiudiGarage').click(function() {
            $('#valore_garage').text('CHIUSA');
            $.get('http://192.168.212.38/chiudi_garage', function(data) {  // Sostituisci con l'IP della tua ESP32
                console.log(data);
            });
        });

        $('#ONluce').click(function() {
            $('#valore_luce span').text('ON');
            $.get('http://192.168.212.235/luce?cmd=accendi', function(data) {  // Sostituisci con l'IP della tua ESP32
                console.log(data);
                aggiornaDatiStato();
            });
        });

        $('#chiudiLuce').click(function() {
            $('#valore_luce span').text('OFF');
            $.get('http://192.168.212.235/luce?cmd=spegni', function(data) {  // Sostituisci con l'IP della tua ESP32
                console.log(data);
                aggiornaDatiStato();
            });
        });

        function aggiornaDatiSensori() {
            $.get('get_values.php', function(data) {
                console.log(data);
                let parsedData = JSON.parse(data);
                $('#valore_temperatura').text(parsedData.temperatura);
                $('#valore_umidita').text(parsedData.umidita);
            });
        }
        setInterval(aggiornaDatiSensori, 100000);
        aggiornaDatiSensori();
    });
</script>
</body>
</html>
