<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Rate and Review</title>
</head>
<form action= "rate-review-handler.php" method="post" enctype="multipart/form-data">

        <label for="rating" max="5" min="1">Rate the Location (Between 1-5) </label><br>
        <input type="number" id="fname" name="fname"><br>
        <label for="lname">Review:</label><br>
        <textarea id="box" name="review_box" rows="10" cols="50"> 
        <label for="photo">Upload Photo:</label>
        <input type="file" name="photo" id="photo" accept="image/*" required><br><br>
        <button type = "submit"> Submit Review</button>
            
            <!--Got code for textarea from IS117 final project which involed using a textarea, needed a bigger box for reviews-->
            <!--Code edited by Mikey, VSCode was being weird, had someone else do it instead-->
        </textarea>
      </form>
</html>
