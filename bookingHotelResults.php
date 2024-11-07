<?php
session_start();

// Database connection details
$host = 'node4';
$dbUser = 'test';
$dbPass = 'test';
$dbName = 'it490';

// Connect to the database
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Query to retrieve booking data
$sql = "SELECT guestName, numGuest, checkinDate, checkinTime, checkoutDate, checkoutTime, hotelName FROM bookings ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Results</title>
</head>
<body>
    <h1>Recent Bookings</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
        // Display bookings in a table
        echo "<table border='1'>
                <tr>
                    <th>Guest Name</th>
                    <th>Number of Guests</th>
                    <th>Check-in Date</th>
                    <th>Check-in Time</th>
                    <th>Check-out Date</th>
                    <th>Check-out Time</th>
                    <th>Hotel Name</th>
                </tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['guestName']) . "</td>
                    <td>" . htmlspecialchars($row['numGuest']) . "</td>
                    <td>" . htmlspecialchars($row['checkinDate']) . "</td>
                    <td>" . htmlspecialchars($row['checkinTime']) . "</td>
                    <td>" . htmlspecialchars($row['checkoutDate']) . "</td>
                    <td>" . htmlspecialchars($row['checkoutTime']) . "</td>
                    <td>" . htmlspecialchars($row['hotelName']) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No bookings found.</p>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
