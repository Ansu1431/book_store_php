<?php
include "../includes/config.php"; // Include your database connection

if (isset($_POST['submit'])) {
    $book_name = $_POST['book_name'];
    $book_type = $_POST['book_type']; // new or old
    $book_category = $_POST['book_category']; // adventure, thriller, etc.
    $book_price = $_POST['book_price'];

    // File Upload Handling
    $target_dir = "upload/"; // Folder where images will be stored
    $book_img = $target_dir . basename($_FILES["book_img"]["name"]);
    move_uploaded_file($_FILES["book_img"]["tmp_name"], $book_img);

    // Insert book into database
    $sql = "INSERT INTO books (book_name, book_type, book_catag, book_price, book_img) 
            VALUES ('$book_name', '$book_type', '$book_category', '$book_price', '$book_img')";

    if ($conn->query($sql) === TRUE) {
        echo "Book added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<body>
    <h2>Add a New Book</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Book Name:</label>
        <input type="text" name="book_name" required><br>

        <label>Book Type:</label>
        <select name="book_type">
            <option value="new">New</option>
            <option value="old">Old</option>
        </select><br>

        <label>Category:</label>
        <select name="book_category">
            <option value="adventure">Adventure</option>
            <option value="thriller">Thriller</option>
            <option value="romantic">Romantic</option>
            <option value="comedy">Comedy</option>
        </select><br>

        <label>Price:</label>
        <input type="number" name="book_price" required><br>

        <label>Book Image:</label>
        <input type="file" name="book_img" required><br>

        <button type="submit" name="submit">Add Book</button>
    </form>
</body>
</html>
