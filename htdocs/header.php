<?php
    session_start();

    $PageName = basename($_SERVER['PHP_SELF']);

    // if the page isnt login.php and signupphp... and if we arnt logged in... then go to the login page...
    if ($PageName != 'login.php' && $PageName != 'signup.php'){
        if(!isset($_SESSION["uid"])){
            header("location: login.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8">
        <title>Room Energy Monitor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    </head>

    <body>

<!-- Header -->
        <div class="mainBanner">
            <label class="logo">Room Energy Monitor</label>
            <ul>
                <?php
                    if(isset($_SESSION["uid"])){
                        echo "<li><a href='includes/logout.inc.php'>Sign Out</a></li>";
                    } else {
                        echo "<li><a href='login.php'>Log In</a></li>";
                        echo "<li><a href='signup.php'>Sign Up!</a></li>";
                    }
                ?>
            </ul>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php">Overview</a></li>
                <li><a href="temperature.php">Temperature</a></li>
                <li><a href="lighting.php">Lighting</a></li>
                <li><a href="occupation.php">Occupation</a></li>
            </ul>
        </nav>
<!-- Header End -->