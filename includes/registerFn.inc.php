<?php
include_once 'registerFn.inc.php'; // Ensure this file is included

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    // Use trim() to remove unnecessary spaces
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $address = isset($_POST["address"]) ? trim($_POST["address"]) : '';
    $pwd = isset($_POST["pwd"]) ? trim($_POST["pwd"]) : '';
    $rpwd = isset($_POST["rpwd"]) ? trim($_POST["rpwd"]) : '';
    $number = isset($_POST["number"]) ? trim($_POST["number"]) : '';

    // Debugging: Check what values are actually received
    if (empty($name) || empty($email) || empty($address) || empty($pwd) || empty($rpwd) || empty($number)) {
        die("All fields are required. Debug: " . json_encode($_POST));
    }

    if (invalidPhone($number)) {
        die("Invalid phone number.");
    }
    if (invalidEmail($email)) {
        die("Invalid email format.");
    }
    if (!pwdMatch($pwd, $rpwd)) {
        die("Passwords do not match.");
    }

    // If all checks pass, create user
    createUser($name, $email, $address, $pwd, $number);
}
?>
