<?php
session_start();

// Database connection
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO($dsn, $username, $password, $options);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get review text
    $reviewText = $_POST['review_text'];

    // Check if the file upload is valid
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Get file data
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];
        $fileContent = file_get_contents($fileTmpPath);

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO reviews (review_text, photo_name, photo_size, photo_type, photo_data) VALUES (:reviewText, :fileName, :fileSize, :fileType, :fileContent)");
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':fileSize', $fileSize);
        $stmt->bindParam(':fileType', $fileType);
        $stmt->bindParam(':fileContent', $fileContent, PDO::PARAM_LOB);

        // Check if data insertion was successful
        if ($stmt->execute()) {
            $_SESSION['message'] = "Review and photo submitted successfully!";
        } else {
            $_SESSION['message'] = "Error saving review and photo.";
        }
    } else {
        $_SESSION['message'] = "Error with the uploaded photo.";
    }

    // Redirect back to the form page
    header("Location: rate-review.php");
    exit();
}
?>