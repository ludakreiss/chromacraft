<?php
include('includes/dbh.php');
include('includes/url.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    var_dump($_POST);

    $validator = new Validator([
        'title' => [ValidationRule::Required => true],
        'text' => [ValidationRule::Required => true],
        'hexCode' => [ValidationRule::Required => true, ValidationRule::HexColor => true],
    ]);

    if ($validator->validate($_POST)) {
        $sanitizedText = htmlentities($_POST['text']);
        $sanitizedTitle = htmlentities($_POST['title']);

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $fileDescriptor = $_FILES['picture'];

            if ($fileDescriptor['size'] > 1024 * 1024) {
                echo "Error: Image needs to be smaller than 1 MB";
            } elseif (!in_array($fileDescriptor['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {
                echo "Error: Invalid file type. Allowed types: jpeg, png, jpg";
            } else {
                move_uploaded_file($fileDescriptor['tmp_name'], './assets/images/' . $fileDescriptor['name']);

                $query = $conn->prepare('INSERT INTO blogpost (title, blog_text, picture, hex_code, user_id, date_uploaded) VALUES (?, ?, ?, ?, ?, NOW())');
                $query->execute([$sanitizedTitle, $sanitizedText, $fileDescriptor['name'], $_POST['hexCode'], $user['user_id']]);

                header('Location: browse.php');
                exit;
            }
        } else {
            echo "Error: Field 'picture' is required";
        }
    } else {
        $errorMessages = $validator->getMessages();
        foreach ($errorMessages as $field => $messages) {
            echo "Error in '$field': " . implode(', ', $messages) . "<br>";
        }
        var_dump($errorMessages);
    }
}
?>

<!-- The rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html lang="en">

<?php
include('includes/head.php');
?>

<body>

    <?php 
        include('includes/preloader.php'); 
        include('includes/nav.php'); 
    ?>


    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
                    <div class="row">

                        <!-- Container for "Pick Your New Color!" -->
                        <div class="col-lg-5">
                            <div class="top-week">
                                <div class="heading-section">
                                    <h4><em>Choose Your</em> Color!</h4>
                                </div>
                                <!-- Iro.js color picker container -->
                                <div id="iro-picker"></div>
                                <!-- Display selected color's hex code and color preview box -->
                                <div class="color-container">
                                    <div id="color-preview-box"></div>
                                    <div class="field">
                                        <div class="control has-icons-left" style="width: 8em; margin-right: 1em;">
                                            <input class="input" name="hexCode" id="hexCode" type="text" placeholder="hexCode" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Container for "Share Your Chroma Thoughts!" -->
                        <div class="col-lg-7">
                            <div class="chroma-thoughts">
                                <div class="col-lg-12 text-center">
                                    <div class="heading-section">
                                        <h4><em>Share Your</em> Chroma Thoughts!</h4>
                                    </div>
                                </div>
                                <!-- Inside the existing form -->
                                <div class="col-12 mx-auto">
                                    <form class="d-flex flex-column align-items-center" method="post" enctype="multipart/form-data">
                                        <!-- Existing fields... -->
                                        <div class="col-6 g-4 mt-5">
                                            <div class="input-group-icon">
                                                <label class="form-label" for="title" style="color: dimgrey;">Your Title</label>
                                                <textarea name="title" class="w-100"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-6 g-4 mt-5">
                                            <div class="input-group-icon">
                                                <label class="form-label" for="text" style="color: dimgrey;">Your Thoughts</label>
                                                <textarea name="text" class="w-100"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-6 g-4 mt-5">
                                            <div class="input-group-icon">
                                                <label class="form-label" for="picture" style="color: dimgrey;">Upload Picture</label>
                                                <input type="file" name="picture" class="w-100 custom-cursor-default-hover" accept="image/png,image/jpg,image/jpeg">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="submit" class="save-button" name="submit" fdprocessedid="s1eivf">Submit</button>
                                        </div>

                                        <!-- Hidden input field for hexCode -->
                                        <input type="hidden" name="hexCode" id="hexCodeHidden" value="">
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
include('includes/footer.php');
?>

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <?php
include('includes/scripts.php');
?>

    <script>
        const colorPicker = new iro.ColorPicker('#iro-picker', {
            width: 350,
            color: '#ff0000',
            borderWidth: 4,
            borderColor: '#fff',
        });

        colorPicker.on('color:change', (color) => {
            const hexCode = color.hexString;
            updateHexCode(hexCode); // Update the hexCode value
            updateColorPicker(hexCode); // Update the field background color
        });

        document.getElementById('hexCode').addEventListener('input', function () {
            const enteredHex = this.value;

        if (/^#[0-9A-Fa-f]{6}$/.test(enteredHex)) {
            colorPicker.color.hexString = enteredHex;
            updateHexCode(enteredHex); // Update the hexCode value
            updateColorPicker(enteredHex); // Update the field background color
        }
    });

    function updateHexCode(value) {
        document.getElementById('hexCode').value = value;
        document.getElementById('hexCodeHidden').value = value;
    }

    function updateColorPicker(hexCode) {
        const field = document.getElementById('hexCode');
        field.style.backgroundColor = hexCode;
    }
</script>


</body>

</html>
