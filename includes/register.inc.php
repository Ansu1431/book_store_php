<?php
if (isset($_POST['submit'])) {
    // Accessing all input values from the form
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $number = $_POST['number'] ?? '';
    $pwd = $_POST['pwd'] ?? '';
    $rpwd = $_POST['rpwd'] ?? ''; // ✅ Add this line to capture the confirm password field

    // 1st step: Database connection
    require_once 'config.php';
    require_once 'registerFn.inc.php';

    // Error handling

    // 2nd checking if the phone number is valid
    if (invalidPhone($number)) {
        header("location: ../register.php?error=enterValidNumber");
        exit();
    }

    // 3rd checking if the email is valid
    if (invalidEmail($email)) {
        header("location: ../register.php?error=invalidemail");
        exit();
    }

    // 4th checking if passwords match ✅
    if (!pwdMatch($pwd, $rpwd)) {
        header("location: ../register.php?error=passwordsdonotmatch");
        exit();
    }

    // If all checks pass, create user
    createUser($name, $email, $address, $pwd, $number);
} else {
    header("location: ../register.php");
    exit("some errors");
}
?>
