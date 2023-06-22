<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_manager";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    session_destroy();
}
