<?php
include('includes/dbh.php');
include('includes/url.php');

if ($userId) {
    $query = "SELECT colorcraft.hex_id, colorcraft.hex_code, colorcraft.hex_name 
              FROM colorcraft
              INNER JOIN users ON colorcraft.user_id = users.user_id
              WHERE colorcraft.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);

    // Check if there are any saved colors
    $colors = ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} else {
    // Handle the case where the user is not logged in
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

    <!-- ***** Preloader Start ***** -->
    <?php 
        include('includes/preloader.php'); 
        include('includes/nav.php'); 
    ?>
    <!-- ***** Header Area End ***** -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12">
                <div class="page-content">

                    <!-- ***** Featured Blogs Start ***** -->
                    <div class="row featured-blogs-section d-flex align-items-stretch centred-row">

                        <div class="col-lg-12">
                            <div class="heading-section" style="margin-right: 20px;">
                                <div class="back-button-container">
                                    <button class="back-button" onclick="location.href='profile.php'"><i class="fa fa-arrow-left"></i></button>
                                </div>
                                <h4><em>My</em> Colors</h4>
                            </div>
                        </div>

                        <!-- Color items -->
                        <?php foreach ($colors as $color) : ?>
                            <div class="col-lg-4 mx-auto">
                                <div class="blog-item">
                                    <form action="includes/delete_color.php" method="post">
                                        <div class="item" style="background-color: <?= $color['hex_code'] ?>; border-radius: 8px; padding: 10px;">
                                            <!-- Display the color box -->
                                            <h4><?= $color['hex_code'] ?><br><span><?= $color['hex_name'] ?></span></h4>
                                            <div class="button-container">
                                                <input type="hidden" name="hex_id" value="<?= $color['hex_id'] ?>">
                                                <button type="submit" class="fa fa-trash trash-button" onclick="return confirm('Are you sure you want to delete this color?')"></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- ***** Featured Blogs End ***** -->

                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <?php include('includes/scripts.php'); ?>

</body>

</html>


