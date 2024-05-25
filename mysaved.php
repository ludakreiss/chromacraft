<?php
include('includes/dbh.php');
include('includes/url.php');

$colors = [];
$blogs = [];

if ($userId) {
    // Query for saved colors
    $queryColors = "SELECT saved.hex_id, colorcraft.hex_code, colorcraft.hex_name
                  FROM saved
                  INNER JOIN colorcraft ON saved.hex_id = colorcraft.hex_id
                  WHERE saved.user_id = ?";
                  
    $colorsStmt = $conn->prepare($queryColors);
    $colorsStmt->execute([$userId]);

    // Check if there are any saved colors
    $colors = ($colorsStmt->rowCount() > 0) ? $colorsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

    // Query for saved blogs
    $queryBlogs = "SELECT saved.blog_id, blogpost.title, blogpost.blog_text, users.first_name, users.last_name, blogpost.hex_code
                 FROM saved
                 LEFT JOIN blogpost ON saved.blog_id = blogpost.blog_id
                 JOIN users ON blogpost.user_id = users.user_id
                 WHERE saved.user_id = ?";
                 
    $blogsStmt = $conn->prepare($queryBlogs);
    $blogsStmt->execute([$userId]);

    // Check if there are any saved blogs
    $blogs = ($blogsStmt->rowCount() > 0) ? $blogsStmt->fetchAll(PDO::FETCH_ASSOC) : [];
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
                                <h4><em>My</em> Saves</h4>
                            </div>
                        </div>

                        <!-- Saved Colors -->
                        <?php foreach ($colors as $color) : ?>
                            <div class="col-lg-4 mx-auto">
                                <div class="blog-item">
                                    <form action="includes/delete_saved_colors.php" method="post">
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

                        <!-- Saved Blogs -->
                        <?php foreach ($blogs as $blog) : ?>
                            <div class="col-lg-4 mx-auto">
                                <div class="blog-item">
                                    <form action="includes/delete_saved_blogs.php" method="post">
                                        <div class="item" style="background-color: <?= $blog['hex_code'] ?>; border-radius: 8px; padding: 10px;">
                                            <!-- Display the blog details -->
                                            <h4><?= html_entity_decode($blog['title'])  ?><br><span> by <?= $blog['first_name'] . ' ' . $blog['last_name']?></span></h4>
                                            <div class="button-container">
                                                <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
                                                <button type="submit" class="fa fa-trash trash-button" onclick="return confirm('Are you sure you want to delete this blog?')"></button>
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



