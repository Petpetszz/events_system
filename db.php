<?php
$conn = new mysqli("localhost", "root", "", "events_db1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>