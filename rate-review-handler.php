<?php
require 'vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Set up RabbitMQ client
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=it490';
$username = 'test';
$password = 'test';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (Exception $e) {
    $_SESSION['message'] = "Database connection failed: " . $e->getMessage();
    header('Location: rate-review.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $rateReviewData = [
        'guestName' => $_POST['guestName'] ?? '',
        'review' => $_POST['review'] ?? '',
        'rating' => $_POST['rating'] ?? '',
        'location' => $_POST['location'] ?? '',
        'date' => $_POST['date'] ?? ''
    ];

    // Check for a valid photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileContent = file_get_contents($fileTmpPath);

        // Send data to RabbitMQ
        try {
            $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
            $channel = $connection->channel();

            // Declare queues
            $channel->queue_declare('rate_review_queue', false, true, false, false);
            $channel->queue_declare('rate_review_response_queue', false, true, false, false);

            // Convert review data to JSON
            $dataJson = json_encode($rateReviewData);

            // Create and publish the message
            $message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
            $channel->basic_publish($message, '', 'rate_review_queue');

            // Listen for a response in the response queue
            $response = null;
            $callback = function($responseMsg) use (&$response) {
                $response = json_decode($responseMsg->body, true);
                $_SESSION['responseData'] = $response;
                $responseMsg->ack(); // Acknowledge the response message
            };
            $channel->basic_consume('rate_review_response_queue', '', false, false, false, false, $callback);

            // Wait for response
            while (!$response && $channel->is_consuming()) {
                $channel->wait();
            }

            // Close RabbitMQ connection
            $channel->close();
            $connection->close();

            // Set response message
            if ($response) {
                $_SESSION['message'] = 'Rate review submitted successfully!';
            } else {
                $_SESSION['message'] = 'No response received from review service.';
            }

        } catch (Exception $e) {
            $_SESSION['message'] = 'Error sending review to RabbitMQ: ' . $e->getMessage();
        }

        // Store review and photo data in the database
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (guestName, review, rating, location, visitDate, photo_data) VALUES (:guestName, :review, :rating, :location, :date, :photo)");
            $stmt->bindParam(':guestName', $rateReviewData['guestName']);
            $stmt->bindParam(':review', $rateReviewData['review']);
            $stmt->bindParam(':rating', $rateReviewData['rating']);
            $stmt->bindParam(':location', $rateReviewData['location']);
            $stmt->bindParam(':date', $rateReviewData['date']);
            $stmt->bindParam(':photo', $fileContent, PDO::PARAM_LOB);

            if ($stmt->execute()) {
                $_SESSION['message'] .= " Review and photo saved to the database successfully!";
            } else {
                $_SESSION['message'] .= " Error saving review and photo to the database.";
            }

        } catch (Exception $e) {
            $_SESSION['message'] .= ' Database error: ' . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Error with the uploaded photo.";
    }
}

// Redirect back to the review page
header('Location: rate-review.php');
exit;
?>
