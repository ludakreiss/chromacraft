<?php 
include('includes/dbh.php');

if($userId){
        $query = "SELECT blogpost.blog_id, blogpost.title, blogpost.blog_text, blogpost.hex_code, blogpost.user_id, COUNT(saved.blog_id) AS saves_count,
        users.first_name, users.last_name
        FROM blogpost 
        LEFT JOIN saved ON blogpost.blog_id = saved.blog_id 
        LEFT JOIN users ON blogpost.user_id = users.user_id
        GROUP BY blogpost.blog_id, blogpost.title, blogpost.blog_text, blogpost.hex_code, blogpost.user_id, users.first_name, users.last_name
        ORDER BY saves_count DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Check if there are any saved blogs
    if ($stmt->rowCount() > 0) {
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $blogs = array(); // Empty array if no saved blogs are found
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the blog_id from the form
        $blogId = isset($_POST['blog_id']) ? $_POST['blog_id'] : '';

        // Check if the blog is not already saved by the user
        $queryCheckSaved = "SELECT * FROM saved WHERE user_id = ? AND blog_id = ?";
        $checkSavedStmt = $conn->prepare($queryCheckSaved);
        $checkSavedStmt->execute([$userId, $blogId]);

        if ($checkSavedStmt->rowCount() == 0) {
            // Blog is not saved, so save it
            $querySaveBlog = "INSERT INTO saved (user_id, blog_id) VALUES (?, ?)";
            $saveBlogStmt = $conn->prepare($querySaveBlog);
            $saveBlogStmt->execute([$userId, $blogId]);

            // You can perform additional actions here if needed

            // Redirect back to the page where the user came from (refresh the page)
            header("Location: topblogs.php");
            exit();
        }
    }
} else{
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
    

    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12">
                <div class="page-content">

                    <!-- Featured Blogs Start -->
                    <div class="row featured-blogs-section d-flex align-items-stretch centred-row">

                        <div class="col-lg-12">
                            <div class="heading-section" style="margin-right: 20px;">
                                <div class="back-button-container">
                                    <button class="back-button" onclick="location.href='browse.php'"><i class="fa fa-arrow-left"></i></button>
                                </div>
                                <h4><em>More</em> Blogs</h4>
                            </div>
                        </div>

                        <!-- Blog Display -->
                        <?php foreach ($blogs as $blog) { ?>
                            <div class="col-lg-4 mx-auto">
                                <!-- Wrap the blog item with an anchor tag -->
                                <a href="readblog.php?blog_id=<?= $blog['blog_id'] ?>" class="blog-item-link">
                                    <div class="blog-item">
                                        <form action="topblogs.php" method="post">
                                            <div class="item" style="background-color: <?= $blog['hex_code'] ?>; border-radius: 8px; padding: 10px;">
                                                <!-- Display the color box -->
                                                <h4><?= html_entity_decode($blog['title']) ?><br><span> by <?= $blog['first_name'] ?> <?= $blog['last_name'] ?></span></h4>
                                                <div class="button-container">
                                                    <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
                                                    <button type="submit" class="fa fa-save save-button"></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Featured Blogs End -->
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <?php include('includes/scripts.php'); ?>

</body>

</html>

