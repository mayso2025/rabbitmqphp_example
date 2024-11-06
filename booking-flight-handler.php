<?php
session_start();
require_once 'vendor/autoload.php'; // Ensure you have the PHP AMQP library loaded
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$dsn = 'mysql:host=localhost;dbname=your_database'; 
$username = 'admin';
$password = '12345';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO($dsn, $username, $password, $options);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather form data
    $ticketOwner = $_POST['ticketOwner'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departureDate = $_POST['departureDate'];
    $returnDate = $_POST['returnDate'];

    // Validate required fields
    if (empty($ticketOwner) || empty($origin) || empty($destination) || empty($departureDate) || empty($returnDate)) {
        $_SESSION['message'] = 'Please fill in all fields.';
        header('Location: form-page.php'); // Redirect to form page with message
        exit;
    }

    // Connect to RabbitMQ
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    // Declare the queue
    $channel->queue_declare('flight_booking_queue', false, true, false, false);

    // Prepare the data to send
    $bookingData = [
        'ticketOwner' => $ticketOwner,
        'origin' => $origin,
        'destination' => $destination,
        'departureDate' => $departureDate,
        'returnDate' => $returnDate
    ];

    // Convert to JSON
    $message = new AMQPMessage(json_encode($bookingData), ['delivery_mode' => 2]);

    // Send the message
    $channel->basic_publish($message, '', 'flight_booking_queue');

    // Close the channel and connection
    $channel->close();
    $connection->close();

    // Set success message and redirect
    $_SESSION['message'] = 'Flight booking submitted successfully!';
    header('Location: form-page.php'); // Redirect to form page with message
    exit;
}
?>
