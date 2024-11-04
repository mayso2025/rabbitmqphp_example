<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

session_start();

try {
    // Connect to RabbitMQ server
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    // Declare the request queue
    $channel->queue_declare('flight_booking_queue', false, true, false, false);
    
    // Declare the response queue (temporary queue for each request)
    list($responseQueueName, ,) = $channel->queue_declare("", false, false, true, false);
    
    // Collect form data
    $flightBookingData = [
        'guestName' => $_POST['guestName'] ?? '',
        'numGuest' => $_POST['numGuest'] ?? '',
        'checkinDate' => $_POST['checkinDate'] ?? '',
        'checkinTime' => $_POST['checkinTime'] ?? '',
        'checkoutDate' => $_POST['checkoutDate'] ?? '',
        'checkoutTime' => $_POST['checkoutTime'] ?? '',
        'location' => $_POST['location'] ?? '',
        'responseQueue' => $responseQueueName // Include response queue in the message
    ];

    // Convert booking data to JSON
    $dataJson = json_encode($flightBookingData);

    // Create a message with the data
    $message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

    // Publish the message to the queue
    $channel->basic_publish($message, '', 'flight_booking_queue');

    // Listen to the response queue
    $response = null;

    $callback = function ($msg) use (&$response) {
        $response = json_decode($msg->body, true);
    };

    $channel->basic_consume($responseQueueName, '', false, true, false, false, $callback);

    // Wait for response
    while (!$response) {
        $channel->wait();
    }

    // Close the channel and connection
    $channel->close();
    $connection->close();

    // Set a success message with response data
    $_SESSION['message'] = 'Flight booking confirmed: ' . ($response['confirmation'] ?? 'Unknown');

} catch (Exception $e) {
    // Set an error message in case of failure
    $_SESSION['message'] = 'Error: ' . $e->getMessage();
}

// Redirect back to bookingflight.php
header('Location: bookingflight.php');
exit;
?>
