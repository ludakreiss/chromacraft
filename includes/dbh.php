<?php
include('classes/Validator.php');

$dsn = "mysql:host=localhost;dbname=chromacraft";
$dbusername= "root";
$dbpassword= "";


    $conn = new PDO($dsn, $dbusername, $dbpassword);

    session_start();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
    $user = null;
    if ($userId) {
        $query = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
        $query->execute([$userId]);
        $user = $query->fetch();
    }

     
    