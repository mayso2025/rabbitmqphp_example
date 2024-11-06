<?php
session_start(); 
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Connect to RabbitMQ server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declare queues for processing rate reviews
$channel->queue_declare('rate_review_queue', false, true, false, false);
$channel->queue_declare('rate_review_response_queue', false, true, false, false);

// Database connection (replace with actual database credentials)
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'db_user', 'db_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the search button is clicked
if (isset($_GET['search'])) {
  // Get the user input
  $keyword = $_GET['keyword'];
  $category = $_GET['category'];
  // Validate the user input
  if (empty($keyword) && empty($category)) {
    // Display an error message if both inputs are empty
    echo "<p>Please enter a keyword or select a category.</p>";
  } else {
    // Build the query based on the user input
    $sql = "SELECT * FROM products WHERE ";
    $params = [];
    if (!empty($keyword)) {
      // Add a condition for the keyword
      $sql .= "name LIKE :keyword OR description LIKE :keyword";
      $params[':keyword'] = "%$keyword%";
    }
    if (!empty($category)) {
      // Add a condition for the category
      if (!empty($keyword)) {
        // Add an AND operator if the keyword is not empty
        $sql .= " AND ";
      }
      $sql .= "category = :category";
      $params[':category'] = $category;
    }
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    // Fetch the results as an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Display the number of results
    echo "<p>Found " . count($results) . " results.</p>";
  }
}        

?>
