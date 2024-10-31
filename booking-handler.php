<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connect to RabbitMQ server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declare a queue to send messages to
$channel->queue_declare('booking_queue', false, true, false, false);

// Collect form data
$bookingData = [
    'guestName' => $_POST['guestName'] ?? '',
    'numGuest' => $_POST['numGuest'] ?? '',
    'checkinDate' => $_POST['checkinDate'] ?? '',
    'checkinTime' => $_POST['checkinTime'] ?? '',
    'checkoutDate' => $_POST['checkoutDate'] ?? '',
    'checkoutTime' => $_POST['checkoutTime'] ?? '',
    'location' => $_POST['location'] ?? ''
];

// Convert booking data to JSON
$dataJson = json_encode($bookingData);

// Create a message with the data
$message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

// Publish the message to the queue
$channel->basic_publish($message, '', 'booking_queue');

// Close the channel and connection
$channel->close();
$connection->close();

// Redirect or display a success message
session_start();
$_SESSION['message'] = 'Booking submitted successfully!';
header('Location: booking.php');
exit;
?>
