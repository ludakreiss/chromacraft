<?php
include('dbh.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the hex_id from the POST data
    $hexId = $_POST['blog_id'];

    $deleteQuery = $conn->prepare('DELETE FROM blogpost WHERE blog_id = ? AND user_id = ?');
    $deleteQuery->execute([$blogId, $userId]);

    header('Location: ../myblogs.php');
    exit;
}
