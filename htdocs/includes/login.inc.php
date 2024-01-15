<?php
if (isset($_POST["login"])){
    $LoginUsername = $_POST["SubmittedUsername"];
    $LoginPassword = $_POST["SubmittedPassword"];
    
    require_once '../dbh.php';

    $sql = "SELECT idUsers, Hashed_Password FROM Users WHERE Users.User_Username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $LoginUsername);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($result){
            $DBpassword = mysqli_fetch_assoc($result);
            if ($DBpassword && password_verify($LoginPassword, $DBpassword['Hashed_Password'])){
                session_start();
                $_SESSION["uid"] = $DBpassword["idUsers"];
                header ("location: ../index.php");
                exit();
            } else {
                header("location: ../login.php");
                echo "Invalid Username or Password, please try again";
                exit();
            }
        } else {
            header("location: ../login.php");
            echo "Something went wrong, please try again";
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        header("location: ../login.php");
        echo "Something went wrong, please try again";
        exit();
    }
} else {
    header("location: ../login.php");
    exit();
}
?>