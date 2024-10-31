<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Booking Flight</title>
</head>
<!-- FORM IS NOW COMPLETE, completed/edited by Mikey  -->
<?php  //Display Success or Error Message If able to Display//
if (isset($_SESSION['message'])){
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);// clear the message after display 
}
?>
<form action= "rate-review-handler.php" method="post" enctype="multipart/form-data">
        <label for="ticketOwner">Name of ticket owner</label> <br> 
        <input type="text" name="ticketOwner"> <br>  
        <label for="origin">Where are you flying from?</label><br> 
        <input type="text" name="origin"> <br> 
        <label for="destination">What is your destination?</label><br> 
        <input type="text" name="destination"> <br> 
        <label for="departureDate">When are you to depart? </label><br> 
        <input type="date" name="departureDate"> <br> 
        <label for="returnDate"> When are you to return?</label><br> 
        <input type="date" name="returnDate"> <br> 
      </form>
</html>