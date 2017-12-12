
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check the connection
if($conn -> connect_error ){
    die("DataBase Connection Failed: " . $conn->connect_error);
}

?>
