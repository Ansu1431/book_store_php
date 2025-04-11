<?php
$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "book-store"; // Make sure this is correct

// Create connection without database
$conn = new mysqli($serverName, $dBUsername, $dBPassword);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dBName";
if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists!\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the database to create tables
$conn->select_db($dBName);

// SQL to create the 'users' table
$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    usersId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usersName VARCHAR(255) NOT NULL,
    usersEmail VARCHAR(255) NOT NULL UNIQUE,
    usersUid VARCHAR(255) NOT NULL UNIQUE,
    usersPwd VARCHAR(255) NOT NULL
)";

if ($conn->query($sqlUsers) === TRUE) {
    echo "Table 'users' created successfully or already exists!\n";
} else {
    echo "Error creating table 'users': " . $conn->error . "\n";
}

// SQL to create the 'books' table
$sqlBooks = "CREATE TABLE IF NOT EXISTS books (
    bookId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bookTitle VARCHAR(255) NOT NULL,
    bookAuthor VARCHAR(255) NOT NULL,
    bookPrice DECIMAL(10, 2) NOT NULL,
    bookStock INT(11) UNSIGNED NOT NULL DEFAULT 0,
    bookDescription TEXT
)";

if ($conn->query($sqlBooks) === TRUE) {
    echo "Table 'books' created successfully or already exists!\n";
} else {
    echo "Error creating table 'books': " . $conn->error . "\n";
}

// SQL to create the 'orders' table
$sqlOrders = "CREATE TABLE IF NOT EXISTS orders (
    orderId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userId INT(11) UNSIGNED NOT NULL,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10, 2) NOT NULL,
    orderStatus VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (userId) REFERENCES users(usersId)
)";

if ($conn->query($sqlOrders) === TRUE) {
    echo "Table 'orders' created successfully or already exists!\n";
} else {
    echo "Error creating table 'orders': " . $conn->error . "\n";
}

// SQL to create the 'order_items' table
$sqlOrderItems = "CREATE TABLE IF NOT EXISTS order_items (
    itemId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    orderId INT(11) UNSIGNED NOT NULL,
    bookId INT(11) UNSIGNED NOT NULL,
    quantity INT(11) UNSIGNED NOT NULL,
    itemPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orderId) REFERENCES orders(orderId),
    FOREIGN KEY (bookId) REFERENCES books(bookId)
)";

if ($conn->query($sqlOrderItems) === TRUE) {
    echo "Table 'order_items' created successfully or already exists!\n";
} else {
    echo "Error creating table 'order_items': " . $conn->error . "\n";
}

$conn->close();
?>