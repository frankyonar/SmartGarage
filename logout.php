<?php
session_start();

// Distruggi la sessione
session_unset();
session_destroy();

// Cancella i cookie di autenticazione
setcookie('loggedin', '', time() - 3600, "/");
setcookie('user_id', '', time() - 3600, "/");

// Reindirizza alla pagina di login
header('Location: login.php');
exit;
?>
