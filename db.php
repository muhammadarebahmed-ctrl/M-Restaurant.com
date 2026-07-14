<?php
$server = "localhost";
$user = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($server, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
