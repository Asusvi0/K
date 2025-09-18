<?php
$servername = "sql105.infinityfree.com";
$username = "if0_37200567";
$password = "IfZ2XEtcMOQa";
$dbname = "if0_37200567_rlzx177";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>