<?php
if (isset($_POST["submit"])) {
    $Submitted_Username = $_POST["submitted_username"];
    $Submitted_Password = $_POST["submitted_password"];
    $Submitted_Verification_Key = $_POST["submitted_verification_key"];
    $Hashed_Password = password_hash($Submitted_Password, PASSWORD_DEFAULT);

    require_once '../dbh.php';

    $sql = "CALL Signup(?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Error in preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sss", $Submitted_Username, $Hashed_Password, $Submitted_Verification_Key);

    if (mysqli_stmt_execute($stmt)) {
        echo "Sign up successful<br><br>You may now close this window";
    } else {
        echo "Sign up failed<br><br>Please close this window and try again";
    }

    mysqli_stmt_close($stmt);
} else {
    header("location: ../signup.php");
    exit();
}
