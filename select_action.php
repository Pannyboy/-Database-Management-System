<!--Onoma: Panagiotis Kourtis -->
<?php
session_start(); // Ksekinima tou session
?>

<!DOCTYPE html>

<html>

<head lang="en">
    <title>Select Action</title>
    <meta charset="utf-8" />
</head>

<body>
    
    <form method='post' action=''>
    
    <h1>Select action</h1>
    <p>Current user: <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'; ?> </p> <br> <!-- Emfanisi tou trexontos xristi -->

    <label>Select Action</label> <br>
    <select name="sel"> <!-- Epilogi energeias -->
        <option value="Search">Search</option> 
        <option value="Insert">Insert</option>
    </select> <br><br>

    <label>Select Table</label> <br> 
    <select name="table"> <!-- Epilogi pinaka -->
        <option>Authors</option>
        <option>Books</option>
    </select> <br><br><br>
    
    <input type='submit' value='Next'/> <!-- Koumpi ypovolis -->
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Elegxos an to request method einai POST
    if (isset($_POST['sel']) && isset($_POST['table'])) { // Elegxos an yparxoun oi epiloges gia energeia kai pinaka
        $selection = $_POST["sel"]; // Apothikefsi tis epilogis energeias
        $table = $_POST["table"]; // Apothikefsi tou epilogoumenou pinaka
        
        if ($selection == "Search") { // An i epilogi einai "Search"
            header("Location: process_search.php?table=$table"); // Anakateuthinsi stin process_search.php me ton pinaka os parametro
        } elseif ($selection == "Insert") { // An i epilogi einai "Insert"
            header("Location: process_insert.php?table=$table"); // Anakateuthinsi stin process_insert.php me ton pinaka os parametro
        }
        exit; // Exodos apo to script
    } else {
        echo "Please select an action and a table."; // Minima sfalmatos an den yparxei epilogi
    }
}
?>
