<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_platform";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>