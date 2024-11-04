<!DOCTYPE html> 
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intitial-scale=1.0">
        <title>Login </title>
        <link rel="stylesheet" type="text/css" href="style.css">  

    </head>
    <body>
        <div class="container">
            <h2>Login </h2>

	    <?php if (isset($_GET['error'])): ?>
                <div class "error message" style="color: red;">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif;?>




            <form method="POST" action="/rabbitmqphp_example/testRabbitMQClient.php">
                <div class="container">
                    <p>Please fill in all fields </p>
                </div>
                <div class="container darker">
                    <label for="username">Email:</label>
                    <input type="text" id="username" name="username"/> <br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password"/> <br><br>
                    <input type="submit" name="submit" value="Login"/>
                    <a href="register.php" class="button">Register</a>
                </div>
            </form>
        </div>
    </body>
</html>
