<?php
    require_once 'dbh.php';

    function GetApiKeys($userID, $conn) {
        $sql = "SELECT API_id FROM Verification_Keys WHERE user_id = ?";    // Prepare SQL statement
        $stmt = mysqli_prepare($conn, $sql);                                // ---

        if ($stmt) {                                                        // Check prepare is successful
            mysqli_stmt_bind_param($stmt, "s", $userID);                    // bind parameters
            mysqli_stmt_execute($stmt);                                     // excecute statement
            $result = mysqli_stmt_get_result($stmt);                        // fetch result
            if (!$result) {                                                 // check there is a valid result
                mysqli_stmt_close($stmt);                                   // if invalid, close the statement...
                return null;                                                // return null ressult
            }
            $row = mysqli_fetch_assoc($result);                             // if there is, fetch the row
            $api_id = $row['API_id'];                                       // create API_id variable from the row
            mysqli_stmt_close($stmt);                                       // close the statement

            $sql2 = "SELECT * FROM API_Keys WHERE API_id = ?";              // prepare second statement
            $stmt2 = mysqli_prepare($conn, $sql2);                          // ---
            
            if ($stmt2) {                                                   // check prepare is successful
                mysqli_stmt_bind_param($stmt2, "s", $api_id);               // bind parameters
                mysqli_stmt_execute($stmt2);                                // excecute statement
                $result2 = mysqli_stmt_get_result($stmt2);                  // fetch ressult
                if (!$result2) {                                            // check for valid result
                    mysqli_stmt_close($stmt2);                              // if invalid, close statement
                    return null;                                            // return null ressult
                }
                $row = mysqli_fetch_assoc($result2);                        // if there is, fetch the row
                
                $BothKeys = [                                               // create an array variable...
                    'dataKey' => $row['DataStreamReadKey'],                 // holding the data stream channel API key from thingspeak...
                    'analysisKey' => $row['AnalysisReadKey']                // and the analysis API key from thingspeak
                ];
                mysqli_stmt_close($stmt2);                                  // close the statement

                return $BothKeys;                                           // return the array of api keys
            }
        }
    }
?>
