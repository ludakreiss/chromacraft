<?php
include('dbh.php'); 

if ($userId) {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
        // Directory to store uploaded profile pictures
        $uploadDir = '../assets/images/';

        // Get file information
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png");
        
        if (in_array($fileType, $allowedFormats)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $uploadDir . $fileName)) {
                // Update the user's profile picture in the database
                $updateSql = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSql);

                // Assuming $userId is the user's ID
                $updateStmt->execute([$fileName, $userId]); // Store only the file name
                 
                // Redirect back to the profile page
                header("Location: ../profile.php");
                exit();
            } else {
                echo "Error uploading file.";
            }
        }
    }
} else {
    header('Location: ../index.php');
    exit();
}
