<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobportal";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage());
}
