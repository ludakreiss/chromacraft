<?php
include('includes/dbh.php');

$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $query = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $query->execute([$email]);
    $user = $query->fetch();

    // Check if the user is not found or the password doesn't match
    if (!$user || !password_verify($_POST['password'], $user['password'])) {
        $validation['login'] = 'Invalid email or password'; // Set a specific validation message
    } else {
        $validator = new Validator([
            'email' => [
                ValidationRule::Required => true,
                ValidationRule::Email => true
            ],
            'password' => [
                ValidationRule::Required => true,
                ValidationRule::ValidPassword => $user ? $user['password'] : ''
            ]
        ]);

        if ($validator->validate($_POST)) {
            $_SESSION['userId'] = $user['user_id'];
            header('Location: browse.php');
            exit(); // Ensure that the script stops here to prevent further execution
        }

        // Validation failed, get error messages
        $validation = $validator->getMessages();
    }
}
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<?php include('includes/head.php'); ?>

<body>
    <main class="main" id="top">
        <?php include('includes/logo_nav.php'); ?>

        <section class="mt-7 py-0">
            <div class="container">
                <div class="row">
                    <div class="col-12 py-5 py-xl-5 py-xxl-7">
                        <h1 class="display-3 text-1000 fw-normal text-center">Sign in</h1>
                        <form class="d-flex flex-column align-items-center" method="post">
                            <div class="col-6 g-4 mt-5">
                                <div class="input-group-icon">
                                    <label class="form-label visually-hidden" for="email">Email</label>
                                    <input name="email" class="form-control input-box form-chromacraft-control" id="email" type="text" placeholder="Email address" value="<?= $email ?>" />
                                    <?php if (isset($validation['email'])) : ?>
                                        <div class="invalid-feedback">
                                            <?= implode(' ', $validation['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6 g-4 mt-5">
                                <div class="input-group-icon">
                                    <label class="form-label visually-hidden" for="password">Password</label>
                                    <input name="password" class="form-control input-box form-chromacraft-control" id="password" type="password" placeholder="Password" />
                                    <?php if (isset($validation['password'])) : ?>
                                        <div class="invalid-feedback">
                                            <?= implode(' ', $validation['password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6 d-grid mt-5">
                                <button class="btn btn-secondary" type="submit">Sign in</button>
                            </div>
                        </form>
                        <div class="col-12">
                            <p class="text-center mt-3">Don't have an account? <a href="register.php">Sign up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include('includes/scripts.php'); ?>
</body>

</html>
