<?php
    include_once 'header.php';
?>

<!-- Content DIV -->
        <div class="ContentDiv">

        <!-- Sign Up Form Start -->
            <div class="LoginSignup">
                <h1>Sign Up</h1>
                <br><br>
                <form action="includes/signup.inc.php" target="_blank" method="post">
                    <input type="text" name="submitted_username" placeholder="Please enter a username...">
                    <input type="password" name="submitted_password" placeholder="Please enter a password...">
                    <input type="text" name="submitted_verification_key" placeholder="Please enter your verification key...">
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        <!-- Sign Up Form End -->
        
        </div>
<!-- Content DIV End -->
        
<?php
    include_once 'footer.php';
?>