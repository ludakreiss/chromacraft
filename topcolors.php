<?php 
include('includes/dbh.php');

// Check if the user is logged in
if ($userId) {
    var_dump($_POST);
    // Fetch the top colors
    $query = "SELECT colorcraft.hex_id, colorcraft.hex_code, colorcraft.hex_name, COUNT(saved.hex_id) AS saves_count,
    users.first_name, users.last_name
    FROM colorcraft 
    LEFT JOIN saved ON colorcraft.hex_id = saved.hex_id 
    LEFT JOIN users ON colorcraft.user_id = users.user_id
    GROUP BY colorcraft.hex_id, colorcraft.hex_code, colorcraft.hex_name, users.first_name, users.last_name
    ORDER BY saves_count DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Check if there are any saved colors
    if ($stmt->rowCount() > 0) {
        $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $colors = array(); // Empty array if no colors are found
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the hex_id from the form
        $hexId = isset($_POST['hex_id']) ? $_POST['hex_id'] : '';

        // Check if the color is not already saved by the user
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

            if (!$saveColorStmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($conn->errorInfo());
            }
        } 
    }
} else {
    header('Location: index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

    <?php 
        include('includes/preloader.php'); 
        include('includes/nav.php'); 
    ?>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12">
                <div class="page-content">

                    <!-- Featured Colors Start -->
                    <div class="row featured-blogs-section d-flex align-items-stretch centred-row">

                        <div class="col-lg-12">
                            <div class="heading-section" style="margin-right: 20px;">
                                <div class="back-button-container">
                                    <button class="back-button" onclick="location.href='browse.php'"><i class="fa fa-arrow-left"></i></button>
                                </div>
                                <h4><em>More</em> Colors</h4>
                            </div>
                        </div>

                        <!-- Color Display -->
                        <?php foreach ($colors as $color) { ?>
                            <div class="col-lg-4 mx-auto">
                                <div class="blog-item">
                                    <form action="topcolors.php" method="post">
                                        <div class="item" style="background-color: <?= $color['hex_code'] ?>; border-radius: 8px; padding: 10px;">
                                            <!-- Display the color box -->
                                            <h4><?= $color['hex_code'] ?><br><span><?= $color['hex_name'] ?> by <?= $color['first_name'] ?> <?= $color['last_name'] ?></span></h4>
                                            <div class="button-container">
                                                <input type="hidden" name="hex_id" value="<?= $color['hex_id'] ?>">
                                                <button type="submit" class="fa fa-save save-button"></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Featured Colors End -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <?php include('includes/scripts.php'); ?>

</body>

</html>



