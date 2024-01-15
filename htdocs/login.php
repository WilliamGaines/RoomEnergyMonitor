<?php
    include_once 'header.php';
?>

<!-- Content DIV -->
        <div class="ContentDiv">

        <!-- Login Form Start -->
            <div class="LoginSignup">
                <h1>Log In</h1>
                <br><br>
                <form action="includes/login.inc.php" method="post">
                    <input type="text" name="SubmittedUsername" placeholder="Enter Username...">
                    <input type="password" name="SubmittedPassword" placeholder="Enter Password...">
                    <button type="submit" name="login">Log in</button>
                </form>
            </div>
        <!-- Login Form End -->
        
        </div>
<!-- Content DIV End -->
        
<?php
    include_once 'footer.php';
?>