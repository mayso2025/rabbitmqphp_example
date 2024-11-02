<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Start a session to handle messages
session_start();

try {
    // Connect to RabbitMQ server
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    // Declare queues: one for sending rate review requests, one for receiving responses
    $channel->queue_declare('rate_review_queue', false, true, false, false);
    $channel->queue_declare('rate_review_response_queue', false, true, false, false);

    // Collect form data for the rate review
    $rateReviewData = [
        'guestName' => $_POST['guestName'] ?? '',
        'review' => $_POST['review'] ?? '',
        'rating' => $_POST['rating'] ?? '',
        'location' => $_POST['location'] ?? '',
        'date' => $_POST['date'] ?? ''
    ];

    // Convert review data to JSON
    $dataJson = json_encode($rateReviewData);

    // Create a message with the data
    $message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

    // Publish the message to the request queue
    $channel->basic_publish($message, '', 'rate_review_queue');

    // Listen for a response in the rate_review_response_queue
    $response = null;
    $callback = function($responseMsg) use (&$response) {
        $response = json_decode($responseMsg->body, true);
        $_SESSION['responseData'] = $response;
        $responseMsg->ack(); // Acknowledge the response message
    };

    // Consume one message from the response queue and wait for the callback
    $channel->basic_consume('rate_review_response_queue', '', false, false, false, false, $callback);

    // Wait for the response from the response queue
    while (!$response && $channel->is_consuming()) {
        $channel->wait();
    }

    // Close the channel and connection
    $channel->close();
    $connection->close();

    // Set success message or store response data
    if ($response) {
        $_SESSION['message'] = 'Rate review submitted successfully!';
    } else {
        $_SESSION['message'] = 'No response received from review service.';
    }

} catch (Exception $e) {
    // If there's an error, set an error message
    $_SESSION['message'] = 'Error: ' . $e->getMessage();
}

// Redirect back to the review page
header('Location: reviewpage.php');
exit;
?>
