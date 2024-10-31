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

    // Declare a queue to send messages to
    $channel->queue_declare('flight_booking_queue', false, true, false, false);

    // Collect form data
    $flightBookingData = [
        'guestName' => $_POST['guestName'] ?? '',
        'numGuest' => $_POST['numGuest'] ?? '',
        'checkinDate' => $_POST['checkinDate'] ?? '',
        'checkinTime' => $_POST['checkinTime'] ?? '',
        'checkoutDate' => $_POST['checkoutDate'] ?? '',
        'checkoutTime' => $_POST['checkoutTime'] ?? '',
        'location' => $_POST['location'] ?? ''
    ];

    // Convert booking data to JSON
    $dataJson = json_encode($flightBookingData);

    // Create a message with the data
    $message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

    // Publish the message to the queue
    $channel->basic_publish($message, '', 'flight_booking_queue');

    // Close the channel and connection
    $channel->close();
    $connection->close();

    // Set a success message and redirect
    $_SESSION['message'] = 'Flight booking submitted successfully!';
} catch (Exception $e) {
    // If there's an error, set an error message
    $_SESSION['message'] = 'Error: ' . $e->getMessage();
}

// Redirect back to bookingflight.php
header('Location: bookingflight.php');
exit;
?>
