<?php
include('dbh.php');
include('includes/url.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the hex_id from the POST data
    $hexId = $_POST['hex_id'];
    $deleteQuery = $conn->prepare('DELETE FROM saved WHERE hex_id = ? AND user_id = ?');
    $deleteQuery->execute([$hexId, $userId]);

    header('Location: ../mysaved.php');
    exit;
}



