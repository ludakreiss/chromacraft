<?php
include('includes/dbh.php');
include('includes/url.php');


// Assuming $userId is set, as it seems to be defined in your previous code
if ($userId) {
  var_dump($_POST);
    // Fetch user information from the users table
    $queryUsers = "SELECT first_name, last_name, profile_picture FROM users WHERE user_id = ?";
    $stmtUser = $conn->prepare($queryUsers);
    $stmtUser->execute([$userId]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);


    // Fetch the count of colors created by the user
    $queryColors = "SELECT COUNT(*) AS colorCount FROM colorcraft WHERE user_id = ?";
    $stmtColors = $conn->prepare($queryColors);
    $stmtColors->execute([$userId]);
    $colorCount = ($stmtColors->rowCount() > 0) ? $stmtColors->fetch(PDO::FETCH_ASSOC)['colorCount'] : 0;

    // Fetch the count of blogs created by the user
    $queryBlogs = "SELECT COUNT(*) AS blogCount FROM blogpost WHERE user_id = ?";
    $stmtBlogs = $conn->prepare($queryBlogs);
    $stmtBlogs->execute([$userId]);
    $blogCount = ($stmtBlogs->rowCount() > 0) ? $stmtBlogs->fetch(PDO::FETCH_ASSOC)['blogCount'] : 0;

    $queryTopColors = "SELECT colorcraft.hex_id, colorcraft.hex_code, colorcraft.hex_name, COUNT(saved.id) AS saves
    FROM colorcraft
    LEFT JOIN saved ON colorcraft.hex_id = saved.hex_id
    WHERE colorcraft.user_id = ?
    GROUP BY colorcraft.hex_id, colorcraft.hex_code, colorcraft.hex_name
    ORDER BY saves DESC
    LIMIT 3;";
    $stmtTopColors = $conn->prepare($queryTopColors);
    $stmtTopColors->execute([$userId]);
    $topColors = ($stmtTopColors->rowCount() > 0) ? $stmtTopColors->fetchAll(PDO::FETCH_ASSOC) : array();

    $queryTopBlogs = "SELECT blogpost.blog_id, blogpost.title, blogpost.hex_code, COUNT(saved.id) AS saves 
    FROM blogpost 
    LEFT JOIN saved ON  blogpost.blog_id = saved.blog_id 
    WHERE blogpost.user_id = ? GROUP BY blogpost.blog_id, blogpost.title, blogpost.hex_code 
    ORDER BY saves 
    DESC LIMIT 3;";
    $stmtTopBlogs = $conn->prepare($queryTopBlogs);
    $stmtTopBlogs->execute([$userId]);
    $topBlogs = ($stmtTopBlogs->rowCount() > 0) ? $stmtTopBlogs->fetchAll(PDO::FETCH_ASSOC) : array();


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
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          <!-- Banner Start -->
          <div class="row">
            <div class="col-lg-12">
              <div class="main-profile">
                <div class="row">
                  <div class="col-lg-4">
                    <?php
                      $profilePicture = isset($userData['profile_picture']) ? $userData['profile_picture'] : '';
                    ?>
                    <img src="assets/images/<?= $profilePicture ?>" alt="" style="border-radius: 23px;">
                  </div>
                  <div class="col-lg-4 align-self-center">
                    <div class="main-info header-text">
                      <?php if (isset($userData['first_name'], $userData['last_name'])) : ?>
                        <h2><?= $userData['first_name'] . " " . $userData['last_name'] ?></h2>
                      <?php endif; ?>
                      <div class="main-button" style="margin-top: 20px;">
                        <a href="mysaved.php">My Saved</a>
                      </div>
                      <div class="main-button" style="margin-top: 10px; margin-bottom: 20px;">
                        <a href="logout.php">Log Out</a>
                      </div>
                      <h5>Edit your profile picture:</h5>
                      <form action="includes/upload_profile_picture.php" method="post" enctype="multipart/form-data" style="margin-top: 20px;">
                        <input type="file" name="profile_picture" accept="image/*" required>
                        <div class="main-button" style="margin-top: 10px;">
                          <button type="submit">Edit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-lg-4 align-self-center">
                    <ul>
                      <li>Colors Crafted <span><?= $colorCount ?></span></li>
                      <li>Blogs Crafted <span><?= $blogCount ?></span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Banner End -->

          <!-- Gaming Library Start -->
          <div class="gaming-library profile-library">
            <div class="col-lg-12">
              <div class="heading-section">
                <h4><em>My Most Popular</em> Colors</h4>
              </div>
              <?php foreach ($topColors as $color) : ?>
                <div class="item">
                  <ul>
                    <li>
                      <div class="item" style="background-color: <?= $color['hex_code'] ?>; border-radius: 8px; padding: 25px;"></div>
                    </li>
                    <li><h4><?= $color['hex_name'] ?></h4></li>
                    <li><h4><?= $color['hex_code'] ?></h4></li>
                    <li><h4><?= $color['saves'] ?>  Saves</h4></li>
                  </ul>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="col-lg-12">
              <div class="main-button">
                <a href="mycolors.php">Load More Colors</a>
              </div>
            </div>
          </div>
          <!-- Gaming Library End -->

          <!-- Gaming Library Start -->
          <div class="gaming-library profile-library">
            <div class="col-lg-12">
              <div class="heading-section">
                <h4><em>My Most Popular</em> Blogs</h4>
              </div>
              <?php foreach ($topBlogs as $blog) : ?>
                <div class="item">
                  <ul>
                    <li>
                      <div class="item" style="background-color: <?= $blog['hex_code'] ?>; border-radius: 8px; padding: 25px;"></div>
                    </li>
                    <li><h4><?= html_entity_decode($blog['title']) ?></h4></li>
                    <li><h4><?= $blog['hex_code'] ?></h4></li>
                    <li><h4><?= $blog['saves']?> Saves</h4></li>
                  </ul>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="col-lg-12">
              <div class="main-button">
                <a href="myblogs.php">Load More Blogs</a>
              </div>
            </div>
          </div>
          <!-- Gaming Library End -->

        </div>
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>

  <!-- Scripts -->
  <?php include('includes/scripts.php'); ?>

</body>

</html>
