<!--Onoma: Panagiotis Kourtis -->
<?php
session_start(); // Ksekinima tou session
error_reporting(E_ALL); // Emfanisi olwn twn errors
ini_set('display_errors', 1); // Emfanisi errors stin othoni

if (!isset($_SESSION['user']) || !isset($_SESSION['dbname'])) { // Elegxos an to session exei setarei ta user kai dbname
    header("Location: login.html"); // Anakateuthinsi stin login selida an den exoun setarei
    exit;
}

$DBHOST = "localhost"; // Orismos tou host
$DBUSER = $_SESSION['user']; // Orismos tou user apo to session
$DBPASSWD = "123"; // Orismos tou password tou MySQL
$DBNAME = $_SESSION['dbname']; // Orismos tou onomatos tis vasis apo to session

$conn = new mysqli($DBHOST, $DBUSER, $DBPASSWD, $DBNAME); // Sindesi sti vasi

if ($conn->connect_error) { // Elegxos sindesis sti vasi
    die("Connection failed: " . $conn->connect_error); // Emfanisi minimatos sfalmatos an i sindesi apotygxei
}

$table = $_GET['table']; // Apothikefsi tis timis tou pinaka apo to URL

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Elegxos an to request method einai POST
    if ($table == "Authors") { // An o pinakas einai oi Authors
        $id = $conn->real_escape_string($_POST['id']); // Apothikefsi kai katharismos tou id
        $name = $conn->real_escape_string($_POST['name']); // Apothikefsi kai katharismos tou onomatos
        $surname = $conn->real_escape_string($_POST['surname']); // Apothikefsi kai katharismos tou epithetoy
        $nationality = $conn->real_escape_string($_POST['natio']); // Apothikefsi kai katharismos tis ethnikotitas
        $date = $conn->real_escape_string($_POST['date']); // Apothikefsi kai katharismos tis imerominias gennisis

        $query = "INSERT INTO Authors (ID, Name, Surname, Nationality, DateOfBirth) VALUES ('$id', '$name', '$surname', '$nationality', '$date')"; // Eisagogi dedomenwn ston pinaka Authors
    } elseif ($table == "Books") { // An o pinakas einai oi Books
        $id = $conn->real_escape_string($_POST['id']); // Apothikefsi kai katharismos tou id
        $title = $conn->real_escape_string($_POST['title']); // Apothikefsi kai katharismos tou titlou
        $authorID = $conn->real_escape_string($_POST['authorID']); // Apothikefsi kai katharismos tou authorID
        $year = $conn->real_escape_string($_POST['year']); // Apothikefsi kai katharismos tou etous
        $publisher = $conn->real_escape_string($_POST['publisher']); // Apothikefsi kai katharismos tou ekdoti

        // Eisagogi tou neou syggrafea
        $authorName = $conn->real_escape_string($_POST['authorName']); // Apothikefsi kai katharismos tou onomatos tou syggrafea
        $authorSurname = $conn->real_escape_string($_POST['authorSurname']); // Apothikefsi kai katharismos tou epithetoy tou syggrafea
        $authorNationality = $conn->real_escape_string($_POST['authorNationality']); // Apothikefsi kai katharismos tis ethnikotitas tou syggrafea
        $authorDOB = $conn->real_escape_string($_POST['authorDOB']); // Apothikefsi kai katharismos tis imerominias gennisis tou syggrafea

        $insertAuthorQuery = "INSERT INTO Authors (ID, Name, Surname, Nationality, DateOfBirth) VALUES ('$authorID', '$authorName', '$authorSurname', '$authorNationality', '$authorDOB')"; // Eisagogi dedomenwn ston pinaka Authors
        if ($conn->query($insertAuthorQuery) !== TRUE) { // Elegxos an i eisagogi tou syggrafea egine epitixos
            echo "Error inserting author: " . $conn->error; // Emfanisi minimatos sfalmatos
            exit;
        }

        // Eisagogi tou bibliou me to ID tou neou syggrafea
        $query = "INSERT INTO Books (ID, Title, AuthorID, Year, Publisher) VALUES ('$id', '$title', '$authorID', '$year', '$publisher')"; // Eisagogi dedomenwn ston pinaka Books
    }

    if ($conn->query($query) === TRUE) { // Elegxos an i eisagogi egine epitixos
        echo "New record created successfully"; // Emfanisi epitixias
    } else {
        echo "Error: " . $query . "<br>" . $conn->error; // Emfanisi minimatos sfalmatos
    }
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <title>Insert Record</title>
    <meta charset="utf-8" />
</head>
<body>
    <h1>Catalog insert for table <?php echo $table; ?></h1><br> <!-- Titlos me to onoma tou pinaka -->
    <form method="post" action="process_insert.php?table=<?php echo $table; ?>"> <!-- Form gia tin ypovolh dedomenwn -->
        <?php if ($table == "Authors") { ?> <!-- An o pinakas einai Authors -->
            <label>ID</label>
            <br>
            <input type='text' name='id'/>
            <br><br>
            <label>Name</label>
            <br>
            <input type='text' name='name'/>
            <br><br>
            <label>Surname</label>
            <br>
            <input type='text' name='surname'/>
            <br><br>
            <label>Nationality</label>
            <br>
            <input type='text' name='natio'/>
            <br><br>
            <label>Date of Birth</label>
            <br>
            <input type='date' name='date'/>
            <br><br>
        <?php } elseif ($table == "Books") { ?> <!-- An o pinakas einai Books -->
            <label>ID</label>
            <br>
            <input type='text' name='id'/>
            <br><br>
            <label>Title</label><br>
            <input type='text' name='title'/>
            <br><br>
            <label>Author ID</label>
            <br>
            <input type='text' name='authorID'/>
            <br><br>
            <label>Author Name</label>
            <br>
            <input type='text' name='authorName'/>
            <br><br>
            <label>Author Surname</label><br>
            <input type='text' name='authorSurname'/>
            <br><br>
            <label>Author Nationality</label>
            <br>
            <input type='text' name='authorNationality'/>
            <br><br>
            <label>Author Date of Birth</label>
            <br>
            <input type='date' name='authorDOB'/>
            <br><br>
            <label>Year</label>
            <br>
            <input type='text' name='year'/>
            <br><br>
            <label>Publisher</label>
            <br>
            <input type='text' name='publisher'/>
            <br><br>
        <?php } ?>
        <input type='submit' value='Insert'/> <!-- Koumpi ypovolhs -->
    </form>

    <form method="post" action="select_action.php"> <!-- Form gia epilogh neas energeias -->
        <input type="submit" value="New Action"/>
    </form>
    <form method="post" action="logout.php"> <!-- Form gia logout -->
        <input type="submit" value="Logout"/>
    </form>
</body>
</html>
<?php
$conn->close(); // Kleisimo tis sindesis sti vasi
?>
