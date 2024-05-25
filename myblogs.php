<?php
include('includes/dbh.php');
include('includes/url.php');

if ($userId) {
    // Query for saved blogs
    $sql = "SELECT blog_id, title, blog_text, hex_code
            FROM blogpost
            INNER JOIN users ON blogpost.user_id = users.user_id
            WHERE blogpost.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

    // Check if there are any saved blogs
    $blogs = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

    if (empty($blogs)) {
        $blogs = []; // Empty array if no blogs are found
    }
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

    <?php 
        include('includes/preloader.php'); 
        include('includes/nav.php'); 
    ?>

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
                                <h4><em>My</em> Blogs</h4>
                            </div>
                        </div>

                        <!-- Blog items -->
                        <?php foreach ($blogs as $blog) : ?>
                            <div class="col-lg-4 mx-auto">
                                <div class="blog-item">
                                    <div class="item" style="background-color: <?= $blog['hex_code'] ?>; border-radius: 8px; padding: 10px;">
                                        <!-- Display the color box -->
                                        <h4><?= html_entity_decode($blog['title']) ?><br><span><?= $blog['hex_code'] ?></span></h4>
                                        <div class="button-container">
                                            <!-- Delete Form -->
                                            <form action="includes/delete_blog.php" method="post" style="display: inline-block;">
                                                <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
                                                <button type="submit" class="fa fa-trash trash-button" onclick="return confirm('Are you sure you want to delete this blog?')"></button>
                                            </form>
                                        </div>
                                    </div>
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


