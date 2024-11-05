<?php
session_start();
require 'vendor/autoload.php';
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$bookingRequest = array();
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connect to RabbitMQ server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declare a queue to send messages to
$channel->queue_declare('booking_queue', false, true, false, false);
// Database connection inspired from IT202 Fall 2023 with Professor Matthew Toegel. PD438 10/30/2024//
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'admin';
$password = '12345';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO($dsn, $username, $password, $options);

// Collect form data
$bookingData = [
    'guestName' => $_POST['guestName'] ?? '',
    'numGuest' => $_POST['numGuest'] ?? '',
    'checkinDate' => $_POST['checkinDate'] ?? '',
    'checkinTime' => $_POST['checkinTime'] ?? '',
    'checkoutDate' => $_POST['checkoutDate'] ?? '',
    'checkoutTime' => $_POST['checkoutTime'] ?? '',
    'hotelName' => $_POST['hotelName'] ?? ''  // Corrected from 'location' to 'hotelName'
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
$_SESSION['message'] = 'Booking submitted successfully!';
header('Location: booking.php');
exit;
