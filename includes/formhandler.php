<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form = $_POST;

    // Validate the form
    $validationRules = [
        'email' => ['required' => true, 'email' => true],
        'password' => ['required' => true],
        'confirmPassword' => ['required' => true, 'confirmPassword' => 'password'],
        'fname' => ['required' => true],
        'lname' => ['required' => true],
    ];

    $errorMessages = validateForm($form, $validationRules);

    if (empty($errorMessages)) {
        // Form is valid, check if the email is already in use
        try {
            include("dbh.php");

            $query = $conn->prepare('SELECT * FROM users WHERE email = ?');
            $query->execute([$form['email']]);
            $results = $query->fetchAll();

            if (count($results) > 0) {
                // Email is already in use, redirect back to registration page with an error message
                $errorMessages['email'] = 'Email address is already in use.';
                $errorString = http_build_query($errorMessages);
                header("Location: ../register.php?" . $errorString);
                exit();
            }

            // Hash the password
            $hashedPassword = password_hash($form['password'], PASSWORD_ARGON2ID);

            // Insert the user data into the database
            $query = $conn->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
            $query->execute([$form['email'], $hashedPassword, $form['fname'], $form['lname']]);

            // Redirect to the browse page after successful registration
            header("Location: ../login.php?registered=true");
            exit();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    } else {
        // Form is invalid, redirect back to the registration page with error messages
        $errorString = http_build_query($errorMessages);
        header("Location: ../register.php?" . $errorString);
        exit();
    }
}

function validateForm($form, $validationRules) {
    $errorMessages = [];

    foreach ($validationRules as $field => $rules) {
        foreach ($rules as $rule => $value) {
            switch ($rule) {
                case 'required':
                    if ($value && empty($form[$field])) {
                        $errorMessages[$field] = ucfirst($field) . ' is required.';
                    }
                    break;
                case 'email':
                    if ($value && !filter_var($form[$field], FILTER_VALIDATE_EMAIL)) {
                        $errorMessages[$field] = 'Invalid email address.';
                    }
                    break;
                case 'confirmPassword':
                    if ($value && $form[$value] !== $form[$field]) {
                        $errorMessages[$field] = 'Passwords do not match.';
                    }
                    break;
            }
        }
    }

    return $errorMessages;
}
?>
