<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Booking Hotel</title>
</head>
<?php  //Display Success or Error Message If able to Display//
if (isset($_SESSION['message'])){
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);// clear the message after display 
}
?>

<form action= "booking-handler.php" method="post" enctype="multipart/form-data">
        <label for="guestName">Name of booker</label> <br> 
        <input type="text" name="guestName"> <br> 
        <label for="numGuest"> How many people in total? </label><br> 
        <input type="number" name="numGuest" min="1"> <br> 
        <label for="checkinDate"> What is expected check in date? </label><br> 
        <input type="date" name="checkinDate"> <br> 
        <label for="checkinTime"> What is your expected check in time? </label><br> 
        <input type="time" name="checkinTime"> <br> 
        <label for="checkoutDate"> When is your expected check out date </label><br> 
        <input type="date" name="checkoutDate"> <br> 
        <label for="checkoutTime"> When is your expected check out time </label><br> 
        <input type="time" name="checkoutTime"> <br> 
        <label for="hotelName"> Which hotel will you be booking? </label><br> 
        <input type="text" name="hotelName"> <br> 
      </form>
</html>