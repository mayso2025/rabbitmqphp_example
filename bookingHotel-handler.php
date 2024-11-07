<?php
session_start();
require 'vendor/autoload.php';
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// RabbitMQ connection details
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declare the queue
$channel->queue_declare('booking_queue', false, true, false, false);

// Database connection settings
$host = 'node4';
$dbUser = 'test';
$dbPass = 'test';
$dbName = 'it490';
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    $_SESSION['message'] = "Database connection failed: " . mysqli_connect_error();
    header('Location: bookingHotel.php');
    exit();
}

// Collect form data
$bookingData = [
    'guestName' => $_POST['guestName'] ?? '',
    'numGuest' => $_POST['numGuest'] ?? '',
    'checkinDate' => $_POST['checkinDate'] ?? '',
    'checkinTime' => $_POST['checkinTime'] ?? '',
    'checkoutDate' => $_POST['checkoutDate'] ?? '',
    'checkoutTime' => $_POST['checkoutTime'] ?? '',
    'hotelName' => $_POST['hotelName'] ?? ''
];

// Convert booking data to JSON and send to RabbitMQ
$dataJson = json_encode($bookingData);
$message = new AMQPMessage($dataJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($message, '', 'booking_queue');

// Close RabbitMQ channel and connection
$channel->close();
$connection->close();

// Set success message and redirect
$_SESSION['message'] = 'Booking submitted successfully!';
header('Location: bookingHotel.php');
exit();
?>
