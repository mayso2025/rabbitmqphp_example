<?php
// recommendations_handler.php

// Example function to retrieve recommendations based on budget and space preferences
function getRecommendations($budget, $spacePreferences) {
    $recommendations = [];

    // Mock data - this could be retrieved from a database in a real application
    $spots = [
        ["name" => "Central Park", "type" => "Park", "budget" => "Cheap", "description" => "A large park in NYC", "image" => "testImages/centralpark.jpeg"],
        ["name" => "Fancy Restaurant", "type" => "Restaurant", "budget" => "Expensive", "description" => "Upscale dining experience", "image" => "testImages/fancyrestaurant.jpeg"],
        // Add more spots as needed
    ];

    // Filter spots based on selected preferences
    foreach ($spots as $spot) {
        if (($spot['budget'] === $budget) && in_array($spot['type'], $spacePreferences)) {
            $recommendations[] = $spot;
        }
    }
    
    return $recommendations;
}

// Check if form data was submitted and process it
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $budget = $_POST['budgetOption'] ?? '';
    $spaces = $_POST['spaces'] ?? [];
    $recommendations = getRecommendations($budget, $spaces);
    
    // Pass recommendations data to the consumer file by storing it in session
    session_start();
    $_SESSION['recommendations'] = $recommendations;
    header("Location: recommendations.php");
    exit();
}
?>
