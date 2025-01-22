<?php
$DBHOST = "localhost";
$DBUSER = "root"; // Your MySQL user
$DBPASSWD = ""; // Your MySQL password
$DBNAME = "web_development_login"; // Use the login database

$conn = new mysqli($DBHOST, $DBUSER, $DBPASSWD, $DBNAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$password = "123";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$sqlTom = "UPDATE logintable SET Password='$hashed_password' WHERE Username='tom'";
$sqlMeryl = "UPDATE logintable SET Password='$hashed_password' WHERE Username='meryl'";

if ($conn->query($sqlTom) === TRUE && $conn->query($sqlMeryl) === TRUE) {
    echo "Passwords updated successfully.";
} else {
    echo "Error updating passwords: " . $conn->error;
}

$conn->close();
?>
