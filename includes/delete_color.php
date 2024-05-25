<?php
include('dbh.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the hex_id from the POST data
    $hexId = $_POST['hex_id'];


    $deleteQuery = $conn->prepare('DELETE FROM colorcraft WHERE hex_id = ? AND user_id = ?');
    $deleteQuery->execute([$hexId, $userId]);

    header('Location: ../mycolors.php');
    exit;
}
