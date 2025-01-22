<!--Onoma: Panagiotis Kourtis -->
<?php
session_start(); // Ksekinima tou session
session_unset(); // Katharismos twn metablitwn tou session
session_destroy(); // Katastrofi tou session
header("Location: login.html"); // Anakateuthinsi stin login selida
exit(); // Termatismos tou script
?>
