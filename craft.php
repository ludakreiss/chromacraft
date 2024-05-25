<?php
include('includes/dbh.php');
include('includes/url.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define validation rules for form fields
    $validator = new Validator([
        'colorName' => [ValidationRule::Required => true],
        'hexCode'   => [ValidationRule::Required => true, ValidationRule::HexColor => true]
    ]);

    // Validate the form data
    if ($validator->validate($_POST)) {
        // Sanitize and retrieve form data
        $colorName = $_POST['colorName'];
        $hexCode   = $_POST['hexCode'];
        $userId    = $user['user_id'];

        // Insert color data into the database with the user ID
        $query = $conn->prepare('INSERT INTO colorcraft (user_id, hex_name, hex_code, date_uploaded) VALUES (?, ?, ?, NOW())');
        $query->execute([$userId, $colorName, $hexCode]);

        // Redirect to a success page or perform other actions
        header('Location: browse.php');
        exit();
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

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
                    <!-- Color Crafting Section -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="color-section-container">
                                <div class="color-naming-container">
                                    <div class="heading-section">
                                        <h4><em>Craft & name</em> your color!</h4>
                                    </div>
                                    <form method="post" action="craft.php" id="colorForm">
                                        <div class="field">
                                            <div class="control" style="margin-bottom: 20px;">
                                                <input class="input" name="colorName" id="colorName" type="text" placeholder="Enter color name" required="">
                                            </div>
                                        </div>
                                        <div class="button-container text-center">
                                            <button type="submit" class="fa fa-save save-button"></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Top Colors Section -->
                        <div class="col-lg-6">
                            <div class="top-week">
                                <div class="heading-section">
                                    <h4><em>Pick Your</em> New Color!</h4>
                                </div>
                                <div id="iro-picker"></div>
                                <div class="color-container">
                                    <div id="color-preview-box"></div>
                                    <div class="field">
                                        <div class="control has-icons-left" style="width: 8em; margin-right: 1em;">
                                            <input class="input" name="hexCode" id="hexCode" type="text" placeholder="hexcode" value="" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <?php include('includes/scripts.php'); ?>

    <!-- JavaScript for Color Picker -->
    <script>
        const colorForm = document.getElementById('colorForm');
        const hexCodeInput = document.getElementById('hexCode');
        const colorPicker = new iro.ColorPicker('#iro-picker', {
          width: 350,
          color: '#ff0000', // Initial color
          borderWidth: 4,
          borderColor: '#fff',
        });

        // Event listener for color change
        colorPicker.on('color:change', (color) => {
          updateHexCode(color.hexString);
        });

        // Event listener for hex code input field
        hexCodeInput.addEventListener('input', function () {
          const enteredHex = this.value;

          // Validate the entered hex code
          if (/^#[0-9A-Fa-f]{6}$/.test(enteredHex)) {
            updateColorPicker(enteredHex);
          }
        });

        // Event listener for form submission
        colorForm.addEventListener('submit', (event) => {
          const hexCodeHiddenInput = document.createElement('input');
          hexCodeHiddenInput.type = 'hidden';
          hexCodeHiddenInput.name = 'hexCode';
          hexCodeHiddenInput.value = hexCodeInput.value;
          colorForm.appendChild(hexCodeHiddenInput);
        });

        // Function to update color picker and related elements
        function updateColorPicker(hexCode) {
          colorPicker.color.hexString = hexCode;
          document.getElementById('color-preview-box').style.backgroundColor = hexCode;
          hexCodeInput.style.backgroundColor = hexCode;
          hexCodeInput.value = hexCode;
        }

        // Function to update hex code input field
        function updateHexCode(hexCode) {
          document.getElementById('color-preview-box').style.backgroundColor = hexCode;
          hexCodeInput.style.backgroundColor = hexCode;
          hexCodeInput.value = hexCode;
        }
    </script>
</body>

</html>


