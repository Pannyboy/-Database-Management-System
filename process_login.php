<!--Onoma: Panagiotis Kourtis -->
<?php
session_start(); // Ξεκινάμε το session για τον χρήστη
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ενεργοποιούμε την αναφορά λαθών για debugging

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Ελέγχουμε αν η μέθοδος του αιτήματος είναι POST
    $DBHOST = "localhost";
    $DBUSER = "root"; // Αλλάξτε σε χρήστη MySQL
    $DBPASSWD = ""; // Αλλάξτε σε κωδικό MySQL
    $DBNAME = "web_development_login"; // Αυτή πρέπει να είναι η βάση δεδομένων login

    $conn = new mysqli($DBHOST, $DBUSER, $DBPASSWD, $DBNAME); // Δημιουργούμε σύνδεση με τη βάση δεδομένων

    if ($conn->connect_error) { // Ελέγχουμε για σφάλματα σύνδεσης
        die("Connection failed: " . $conn->connect_error);
    }

    $user = isset($_POST['user']) ? $conn->real_escape_string($_POST['user']) : ''; // Λαμβάνουμε το όνομα χρήστη και το καθαρίζουμε
    $passwd = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : ''; // Λαμβάνουμε τον κωδικό και το καθαρίζουμε
    $dbname = isset($_POST['choice']) ? $conn->real_escape_string($_POST['choice']) : ''; // Λαμβάνουμε τη βάση δεδομένων και το καθαρίζουμε

    if (empty($user) || empty($passwd) || empty($dbname)) { // Ελέγχουμε αν όλα τα πεδία είναι συμπληρωμένα
        echo "Please fill all fields.";
        exit;
    }

    $sql = "SELECT * FROM logintable WHERE Username='$user'"; // Δημιουργούμε το ερώτημα για να βρούμε τον χρήστη
    $result = $conn->query($sql); // Εκτελούμε το ερώτημα

    if ($result->num_rows > 0) { // Ελέγχουμε αν ο χρήστης βρέθηκε
        $row = $result->fetch_assoc();
        if (password_verify($passwd, $row['Password'])) { // Ελέγχουμε αν ο κωδικός ταιριάζει με τον hashed κωδικό στη βάση δεδομένων
            $_SESSION['user'] = $user; // Αποθηκεύουμε το όνομα χρήστη στη συνεδρία
            $_SESSION['dbname'] = $dbname; // Αποθηκεύουμε τη βάση δεδομένων στη συνεδρία
            $_SESSION['login_attempts'] = 0; // Επαναφέρουμε τις προσπάθειες σύνδεσης
            echo "<h1>Process Login</h1>";
            echo "<h2>User '$user' authenticated</h2>";
            echo "<form method='post' action='select_action.php'>";
            echo "<input type='submit' value='Next'/>";
            echo "</form>";
            exit;
        } else {
            handle_failed_login("Wrong password. Updating wrong login attempts."); // Διαχείριση αποτυχημένης προσπάθειας σύνδεσης
        }
    } else {
        handle_failed_login("User does not exist."); // Διαχείριση αποτυχημένης προσπάθειας σύνδεσης
    }
    $conn->close(); // Κλείνουμε τη σύνδεση με τη βάση δεδομένων
} else {
    header("Location: login.html"); // Ανακατεύθυνση αν το αίτημα δεν είναι POST
    exit;
}

function handle_failed_login($message) { // Λειτουργία διαχείρισης αποτυχημένης προσπάθειας σύνδεσης
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0; // Αν δεν υπάρχουν προσπάθειες σύνδεσης, ορίζουμε σε 0
    }

    $_SESSION['login_attempts'] += 1; // Αύξηση του μετρητή προσπαθειών σύνδεσης
    $attempts = $_SESSION['login_attempts'];

    echo "<h1>Process Login</h1>";

    if ($attempts >= 3) { // Έλεγχος για μέγιστο αριθμό προσπαθειών
        if (isset($_SESSION['last_attempt_time'])) {
            $difference = time() - $_SESSION['last_attempt_time'];
            if ($difference <= 30) {
                echo "<h2>User login is blocked</h2>";
                echo "<h2>User is not authenticated. Go back and try again.</h2>";
                exit;
            } else {
                unset($_SESSION['last_attempt_time']);
                $_SESSION['login_attempts'] = 1;
            }
        } else {
            $_SESSION['last_attempt_time'] = time();
            echo "<h2>$message Attempt $attempts.</h2>";
            echo "<h2>Wrong password given for 3 times. Try again in 30s.</h2>";
            echo "<h2>User is not authenticated. Go back and try again.</h2>";
            exit;
        }
    }

    echo "<h2>$message Attempt $attempts.</h2>";
    echo "<h2>User is not authenticated. Go back and try again.</h2>";
    exit;
}
?>
