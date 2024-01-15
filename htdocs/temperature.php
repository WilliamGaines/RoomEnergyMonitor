<?php
    include_once 'header.php';                                  // include Head and header
    include_once 'includes/API_functions.php';                  // include function to retrieve thingspeak API keys

    $ApiKeys = GetApiKeys($_SESSION['uid'], $conn);             // call function to retrieve thingspeak API keys
    if($ApiKeys){                                               // check function has returned something
        $dataKey = $ApiKeys['dataKey'];                         // create API key variable -data stream channel-
        $analysisKey = $ApiKeys['analysisKey'];                 // create API key variable -analysis channel-
    }
?>

<!-- Content DIV -->
        <div class="ContentDiv">
            <div class="MainBody">
                <br><br>
                <!-- Temperature data stream visualisation from thingspeak -->
                <iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2384242/charts/1?bgcolor=%23ffffff&color=%23d62020&results=20&api_key=<?= $dataKey ?>"></iframe>
                <br><br>
                <!-- daily temperature analysis visualisation from thingspeak -->
                <iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2388406/charts/1?bgcolor=%23ffffff&color=%23d62020&results=7&api_key=<?= $analysisKey ?>"></iframe>
            </div>
        </div>
<!-- Content DIV End -->
        
<?php
    include_once 'footer.php';
?>