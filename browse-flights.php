<?php
session_start(); 
//Got code from this tutorial https://www.youtube.com/watch?v=6xdHq2YE0g8 


    $dsn = 'mysql:host=localhost;dbname=your_database';
    $username = 'admin';
    $password = '12345';
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    $pdo = new PDO($dsn, $username, $password, $options);
    
    //copied from Paulo's code 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        // Get review text
        $bookername= $_POST['bookerName']; //bookername
    $numGuest = $_POST['numGuest']; //numGuest (insert random test)
    $checkinTime = $_POST['checkinTime']; //checkinTime
    $checkinDate = $_POST['checkinDate']; //checkinDate
    $checkoutTime = $_POST['checkoutTime']; //checkoutTime
    $checkoutDate = $_POST['checkoutDate']; //checkoutDate
    $hotelName = $_POST['hotelName']; //hotelName 

    $bookingRequest[] = $bookername;
    $bookingRequest[] = $numGuest;
    $bookingRequest[] = $checkinTime;
    $bookingRequest[] = $checkinDate;
    $bookingRequest[] = $checkoutTime;
    $bookingRequest[] = $checkoutDate;
    $bookingRequest[] = $hotelName;
    }

        
    
        // Check if the file upload is valid
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Get file data
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];
            $fileContent = file_get_contents($fileTmpPath);
    
    
            $requestReview['type'] = "rateAndReview";
            $requestReview['review'] = $reviewText;
            $requestReview['rating'] = $reviewNum;
    
            $response = $client->send_request($requestReview); //sends data up the MQ
    
            echo $response //just to test 
        }
?>


