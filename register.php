<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>


  <?php
    include('includes/preloader.php');
    include('includes/logo_nav.php');
  ?>

  <main class="main" id="top">
    <section class="mt-7 py-0">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="display-3 text-1000 fw-normal text-center">Craft An Account</h1>
            <form action="includes/formhandler.php" class="d-flex flex-column align-items-center" method="post">
              <div class="col-6 g-4 mt-5">
                <div class="input-group-icon">
                  <label class="form-label visually-hidden" for="email">Email</label>
                  <input class="form-control input-box form-chromacraft-control" name="email" id="email" type="text" placeholder="Email address" required />
                </div>
              </div>
              <div class="col-6 g-4 mt-5">
                <div class="input-group-icon">
                  <label class="form-label visually-hidden" for="password">Password</label>
                  <input class="form-control input-box form-chromacraft-control" name="password" id="password" type="password" placeholder="Password" required />
                </div>
              </div>
              <div class="col-6 g-4 mt-5">
                <div class="input-group-icon">
                  <label class="form-label visually-hidden" for="confirm">Confirm password</label>
                  <input class="form-control input-box form-chromacraft-control" name="confirmPassword" id="confirm" type="password" placeholder="Confirm password" required />
                </div>
              </div>
              <div class="col-6 g-4 mt-5">
                <div class="input-group-icon">
                  <label class="form-label visually-hidden" for="fname">First name</label>
                  <input class="form-control input-box form-chromacraft-control" name="fname" id="fname" type="text" placeholder="First name" required />
                </div>
              </div>
              <div class="col-6 g-4 mt-5">
                <div class="input-group-icon">
                  <label class="form-label visually-hidden" for="lname">Last name</label>
                  <input class="form-control input-box form-chromacraft-control" name="lname" id="lname" type="text" placeholder="Last name" required />
                </div>
              </div>
              <div class="col-6 d-grid mt-6" style="margin-top: 20px";>
                <button class="btn btn-secondary" type="submit">Craft Your Account</button>
              </div>
            </form>
            <div>
              <p class="text-center mt-3">Already have an account? <a href="login.php">Log in</a></p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('includes/footer.php'); ?>

  <!-- Scripts -->
  <?php include('includes/scripts.php'); ?>

</body>

</html>
