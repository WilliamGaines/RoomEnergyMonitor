<?php
    include_once 'header.php';                                  // include Head and header
    include_once 'includes/API_functions.php';                  // include function to retrieve thingspeak API keys

    $ApiKeys = GetApiKeys($_SESSION['uid'], $conn);             // call function to retrieve thingspeak API keys
    if($ApiKeys){                                               // check function has returned something
        $analysisKey = $ApiKeys['analysisKey'];                 // create API key variable -data stream-
    }
?>

<!-- Content DIV -->
        <div class="ContentDiv">
            <div class="MainBody">
                <br><br>
                <!-- General energy consumption visualisation from thingspeak -->
                <iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2388406/charts/4?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&type=line&update=15&api_key=<?= $analysisKey ?>"></iframe>
            </div>
        </div>
<!-- Content DIV End -->

<?php
    include_once 'footer.php';
?>