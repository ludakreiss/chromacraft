<?php
include('dbh.php');
include('includes/url.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the hex_id from the POST data
    $blogId = $_POST['blog_id'];


        $deleteQuery = $conn->prepare('DELETE FROM saved WHERE blog_id = ? AND user_id = ?');
        $deleteQuery->execute([$blogId, $userId]);

    header('Location: ../mysaved.php');
    exit;
}
