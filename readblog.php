<?php
include('includes/dbh.php');
include('includes/url.php');

// Check if blog_id is set and fetch the blog details
if (isset($_GET['blog_id'])) {
    $blogId = $_GET['blog_id'];

    $sql = "SELECT blogpost.blog_id, blogpost.title, blogpost.blog_text, blogpost.hex_code, blogpost.picture, users.first_name, users.last_name, users.profile_picture
            FROM blogpost
            JOIN users ON blogpost.user_id = users.user_id
            WHERE blogpost.blog_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$blogId]);

    // Check if the blog exists
    if ($stmt->rowCount() > 0) {
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    }
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
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">

                    <!-- Featured Blogs Start -->
                    <div class="row featured-blogs-section d-flex align-items-stretch centred-row">

                        <div class="col-lg-12">
                            <div class="heading-section" style="margin-right: 20px; margin-bottom:20px;">
                                <div class="back-button-container">
                                    <button class="back-button" onclick="location.href='topblogs.php'"><i class="fa fa-arrow-left"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 text-center">
                            <!-- Display User's Picture and Name -->
                            <div class="user-picture-container">
                                <form action="topblogs.php" method="post">
                                    <div class="profile-picture" style="display: inline-block; margin-right: 10px; border-radius: 20%; overflow: hidden;">
                                        <!-- Fetch user's picture based on user_id from the blogpost table -->
                                        <img src="assets/images/<?= $blog['profile_picture'] ?>" alt="Profile Picture" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <div class="heading-section text-center">
                                        <h4><em><?= $blog['first_name'] ?>'s</em> Picture!</h4>
                                    </div>
                                    <div class="blogpicture" style="display: inline-block; margin-top: 10px;">
                                        <!-- Fetch additional picture from the blogpost table -->
                                        <img src="assets/images/<?= $blog['picture'] ?>" alt="blogpicture" style="width: 400px; height: 400px; object-fit: cover; border-radius: 5%;">
                                    </div>
                                    <div class="button-container">
                                        <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
                                        <button type="submit" class="fa fa-save save-button" style="margin-top: 20px;"> </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-7 text-center">
                            <div class="chroma-thoughts text-center">
                                <div class="col-lg-12 text-center">
                                    <!-- Display Blog Title and Thoughts -->
                                    <div class="profile-picture" style="display: inline-block; margin-right: 10px; border-radius: 20%; overflow: hidden;">
                                        <!-- Fetch user's picture based on user_id from the database -->
                                        <img src="assets/images/<?= $blog['profile_picture'] ?>" alt="Profile Picture" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <!-- Heading for "User's Chroma Thoughts" -->
                                    <div class="heading-section">
                                        <h4><em><?= $blog['first_name'] ?>'s</em> Chroma Thoughts!</h4>
                                    </div>
                                </div>

                                <!-- Display Blog Title, Thoughts, and Hex Code -->
                                <div class="col-6 g-4 mt-5 mx-auto">
                                    <div class="input-group-icon">
                                        <h4><?= html_entity_decode($blog['title']) ?></h4>
                                    </div>
                                </div>

                                <div class="col-6 g-4 mt-5 mx-auto" style="width: 80%;">
                                    <p style='font-size: large; color: #CCC;'><?= html_entity_decode($blog['blog_text']) ?></p>
                                </div>

                                <div class="col-6 g-4 mt-5 mx-auto">
                                    <div class="input-group-icon">
                                        <h4><label class="form-label" for="hexcode" style="color: dimgrey;">Hex Code</label></h4>
                                        <div class="item" style="background-color: <?= $blog['hex_code'] ?>; border-radius: 8px; padding: 30px;">
                                            <h5><?= $blog['hex_code'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Featured Blogs End -->

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

