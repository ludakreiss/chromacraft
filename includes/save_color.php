<?php
// Assuming you have included your database connection file
include('dbh.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the hex_id from the form
    $hexId = isset($_POST['hex_id']) ? $_POST['hex_id'] : '';

     // Check if the form is submitted and the color is not already saved by the user
    if (isset($_POST['submit']) && empty($_POST['colorSaved'])) {
        $queryCheckSaved = "SELECT * FROM saved WHERE user_id = ? AND hex_id = ?";
        $checkSavedStmt = $conn->prepare($queryCheckSaved);
        $checkSavedStmt->execute([$userId, $hexId]);

        if ($checkSavedStmt->rowCount() == 0) {
            // Color is not saved, so save it
            $querySaveColor = "INSERT INTO saved (user_id, hex_id) VALUES (?, ?)";
            $saveColorStmt = $conn->prepare($querySaveColor);
            $saveColorStmt->execute([$userId, $hexId]);

            // You can perform additional actions here if needed

            // Redirect back to the page where the user came from (refresh the page)
            header("Location: topcolors.php");
            exit();
        } 
    }
}
 else {
    header('Location: index.php');
    exit();
}
