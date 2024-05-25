<?php
include('includes/dbh.php');
include('includes/url.php');

$queryColor = "SELECT colorcraft.hex_id, colorcraft.hex_name, colorcraft.date_uploaded, colorcraft.user_id, COUNT(saved.hex_id) AS saves_count, 
                users.first_name, users.last_name, colorcraft.hex_code 
                FROM colorcraft 
                LEFT JOIN saved ON colorcraft.hex_id = saved.hex_id
                LEFT JOIN users ON colorcraft.user_id = users.user_id 
                WHERE colorcraft.date_uploaded >= DATE(NOW()) - INTERVAL 7 DAY
                GROUP BY colorcraft.hex_id, colorcraft.hex_name, colorcraft.date_uploaded, colorcraft.user_id, colorcraft.hex_code 
                ORDER BY saves_count DESC
                LIMIT 3";

$queryBlog = "SELECT blogpost.blog_id, blogpost.title, blogpost.blog_text, blogpost.hex_code, blogpost.picture, blogpost.date_uploaded, users.first_name, users.last_name, COUNT(saved.blog_id) AS saves_count
                FROM blogpost
                LEFT JOIN saved ON blogpost.blog_id = saved.blog_id
                LEFT JOIN users ON blogpost.user_id = users.user_id
                WHERE blogpost.date_uploaded >= CURDATE() - INTERVAL 7 DAY
                GROUP BY blogpost.blog_id, blogpost.title, blogpost.blog_text, blogpost.hex_code, blogpost.picture, blogpost.date_uploaded, users.first_name, users.last_name
                ORDER BY saves_count DESC
                LIMIT 6";

$stmt = $conn->prepare($queryBlog);
$stmt->execute();
$topBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare($queryColor);
$stmt->execute();
$topColors = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

                <!-- ***** Featured Blogs Start ***** -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="featured-games header-text">
                            <div class="heading-section text-center">
                                <h4><em>Featured</em> Blogs</h4>
                            </div>
                            <div class="owl-features owl-carousel">
                                <?php foreach ($topBlogs as $blog) : ?>
                                    <a href="readblog.php?blog_id=<?= $blog['blog_id'] ?>" class="blog-item-link">
                                        <div class="item">
                                            <div class="thumb">
                                                <img src="<?= 'assets/images/' . $blog['picture'] ?>" alt="Blog Image">
                                            </div>
                                            <h4><?= $blog['title'] ?><br><span>by <?= $blog['first_name'] ?> <?= $blog['last_name'] ?></span></h4>
                                            <ul>
                                                <li><i class="fa fa-save" style="color: <?= $blog['hex_code'] ?>"></i> <?= $blog['saves_count'] ?></li>
                                            </ul>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>

                            <div class="text-button text-center" style='margin-top: 30px;'>
                                <a href="topblogs.php">View More Blogs</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="top-downloaded">
                            <div class="heading-section">
                                <h4><em>Top Colors</em> of the Week</h4>
                            </div>
                            <ul>
                                <?php foreach ($topColors as $color) : ?>
                                    <li style="display: flex; align-items: center">
                                        <div class="color-box" style="border-radius: 15px; padding: 30px; background-color: <?= $color['hex_code'] ?>"></div>
                                        <div class="color-details" style="flex: 1; margin-left: 15px;">
                                            <h4><?= $color['hex_name'] ?></h4>
                                            <h6><?= $color['first_name'] ?> <?= $color['last_name'] ?></h6>
                                            <span>
                                                <i class="fa fa-save" style="color: <?= $color['hex_code'] ?>"></i> <?= $color['saves_count'] ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="text-button">
                                <a href="topcolors.php">View More Colors</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ***** Featured blogs End ***** -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<?php include('includes/scripts.php'); ?>

</body>

</html>
