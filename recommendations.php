<!DOCTYPE html>
<html> 
<!-- THE ACTUAL FILE IS NOT TO BE RUN OR CALLED, IT IS SIMPLY A STAGING GROUND FOR WHERE WE NEED TO TEST NEW FEAUTRES AND THEN COPY THEM INTO welcome.php -->
    <body> 
        <p> Choose your preferred budget </p>
    <form> <!-- Code gotten from w3schools https://www.w3schools.com/html/html_form_input_types.asp -->
        <input type="radio" id="cheap" name="cheapOption" value="Cheap">
        <label for="cheapOption">Cheaper Spots</label><br>
        <input type="radio" id="vehicle2" name="expensiveOption" value="Expensive">
        <label for="expensiveOption">Expensive Spots</label><br>
    </form>
    <br>
    <p> Choose your preferred spaces</p>
    <form> <!-- Code gotten from w3schools https://www.w3schools.com/html/html_form_input_types.asp -->
        <input type="checkbox" id="cheap" name="cheapOption" value="Cheap">
        <label for="cheapOption">Parks</label><br>
        <input type="checkbox" id="vehicle2" name="vehicle2" value="Expensive">
        <label for="checkbox">Resturants</label><br>
        <input type="checkbox" id="vehicle2" name="vehicle2" value="Expensive">
        <label for="checkbox">Shopping Stores</label><br>
        <input type="checkbox" id="vehicle2" name="vehicle2" value="Expensive">
        <label for="checkbox">Tourist Spots</label><br>
    </form>
    </body> 
    <br>
    <div> 
        <header> Here are some spots we feel you'd like! </header> 
    <!-- Insert the code that is needed to display the locations and their corresponding images-->
    <!-- Utilize the usage of HTML Cards https://www.w3schools.com/howto/howto_css_cards.asp --> 
     <div class="card"> 
        <img src="testImages/centralpark.jpeg" alt="locImage" style="width:100%"> 
        <div class="container">
            <h4></b>Central Park</b></h4>
            <p>A large park in the center of NYC</p>
    </div>
    </div>
    </div>
    <!-- NOTE!: This is just here for testing purposes to see if the actual styling code works, utilize this in an actual css file (later down the line of course) -->
    <style> 
                .card {
        /* Add shadows to create the "card" effect */
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        }

        /* On mouse-over, add a deeper shadow */
        .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        /* Add some padding inside the card container */
        .container {
        padding: 2px 16px;
        }
    </style>
</html>