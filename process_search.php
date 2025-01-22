<!--Onoma: Panagiotis Kourtis -->
<?php
session_start(); // Ksekinima tou session
error_reporting(E_ALL); // Emfanisi olwn twn errors
ini_set('display_errors', 1); // Emfanisi errors stin othoni

if (!isset($_SESSION['user'])) { // Elegxos an o xristis einai sindedemenos
    header("Location: login.html"); // Anakateuthinsi stin login selida an den einai
    exit;
}

$DBHOST = "localhost"; // Orismos tou host
$DBUSER = $_SESSION['user']; // Orismos tou MySQL user apo to session
$DBPASSWD = "123"; // Orismos tou MySQL password
$DBNAME = $_SESSION['dbname']; // Orismos tou onomatos tis vasis apo to session

$conn = new mysqli($DBHOST, $DBUSER, $DBPASSWD, $DBNAME); // Sindesi sti vasi

if ($conn->connect_error) { // Elegxos sindesis sti vasi
    die("Connection failed: " . $conn->connect_error); // Emfanisi minimatos sfalmatos an i sindesi apotygxei
}

$table = $_GET['table']; // Apothikefsi tou onomatos tou pinaka apo to URL

// Prosdiwrismos twn stilwn analoga me ton pinaka
$columns = [];
if ($table == "Authors") { // An o pinakas einai "Authors"
    $columns = ["ID", "Name", "Surname", "Nationality", "DateOfBirth"]; // Orismos stilwn tou pinaka Authors
} elseif ($table == "Books") { // An o pinakas einai "Books"
    $columns = ["ID", "Title", "AuthorID", "Year", "Publisher"]; // Orismos stilwn tou pinaka Books
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Elegxos an to request method einai POST
    $search = $conn->real_escape_string($_POST['input']); // Apothikefsi kai katharismos tou orou anazitisis
    
    // Kataskevi tou query
    $query = "SELECT * FROM $table WHERE "; // Arxiki meros tou query
    $conditions = [];
    foreach ($columns as $column) { // Gia kathe stili tou pinaka
        $conditions[] = "$column LIKE '%$search%'"; // Prosthesi sindikwn sto query
    }
    $query .= implode(" OR ", $conditions); // Synenwsi twn sindikwn me to "OR"

    $result = $conn->query($query); // Ektelesi tou query
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <title>Search Results</title>
    <meta charset="utf-8" />
</head>
<body>
    <h1>Catalog search for table <?php echo $table; ?></h1><br>
    <form method="post" action="process_search.php?table=<?php echo $table; ?>">
        <label>Choose search type:</label><br>
        <select name="column">
            <?php
            foreach ($columns as $column) { // Gia kathe stili, dimiourgia epilogis
                echo "<option value=\"$column\">$column</option>";
            }
            ?>
        </select><br><br>
        <label>Enter search term:</label><br>
        <input type='text' name='input' /><br><br>
        <input type='submit' value='Search'/>
    </form>

    <?php
    if (isset($result) && $result->num_rows > 0) { // Elegxos an vrethikan apotelesmata
        echo "<h1>Search Results</h1>";
        while ($row = $result->fetch_assoc()) { // Gia kathe apotelesma
            echo "ID: " . $row['ID'];
            if ($table == "Authors") { // An o pinakas einai "Authors"
                echo " - Name: " . $row['Name'];
                echo " - Surname: " . $row['Surname'];
                echo " - Nationality: " . $row['Nationality'];
                echo " - Date of Birth: " . $row['DateOfBirth'];
            } elseif ($table == "Books") { // An o pinakas einai "Books"
                echo " - Title: " . $row['Title'];
                echo " - Author ID: " . $row['AuthorID'];
                echo " - Year: " . $row['Year'];
                echo " - Publisher: " . $row['Publisher'];
            }
            echo "<br>";
        }
    } elseif (isset($result)) { // An den vrethikan apotelesmata
        echo "No results found.";
    }
    ?>

    <form method="post" action="select_action.php">
        <input type="submit" value="New Action"/>
    </form>
    <form method="post" action="logout.php">
        <input type="submit" value="Logout"/>
    </form>
</body>
</html>
<?php
$conn->close(); // Kleisimo tis sindesis sti vasi
?>
