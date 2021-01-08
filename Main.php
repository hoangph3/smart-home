<?php require_once 'database.php';
require_once 'SetData.php';
// Check login or not by retrieved auth
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT * FROM user WHERE ID = 0';
$q = $pdo->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);

if ($data['auth'] == 1) {
    //Feedback
    $Write="<?php $" . "getLEDStatusFromNodeMCU=''; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
    file_put_contents('LEDStatContainer.php',$Write);

    //Control via home assistant
    $sql = "UPDATE statusled SET Stat = ?, Color = ? WHERE ID = 0";
    $q = $pdo->prepare($sql);
    $q->execute(array($Stat,$Color));
    
    //Read sensor
    $sql = 'SELECT * FROM sensor WHERE ID = 0';
    $q = $pdo->prepare($sql);
    $q->execute();
    $sensor = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Smart Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/fire.js"></script>
        <script>
            $(document).ready(function(){
                $("#getLEDStatus").load("LEDStatContainer.php");
                setInterval(function() {
                $("#getLEDStatus").load("LEDStatContainer.php");
                }, 100);
            });
        </script>
    </head>
    <body>
    <div class="w3-container" align="left">
        <h2 style="display: inline-block;"> Light control</h2>
        <a style="float: right; font-size: 24px; font-weight: bold;" href="log_out.php">Log out</a>
        <!-- Controlling voltage -->
        <form action="updateDBLED.php" method="post" id="LED_MAX">
            <input type="hidden" name="Stat" value="100"/>    
        </form>
        <form action="updateDBLED.php" method="post" id="LED_MEDIUM">
            <input type="hidden" name="Stat" value="50"/>    
        </form>
        <form action="updateDBLED.php" method="post" id="LED_MIN">
            <input type="hidden" name="Stat" value="0"/>
        </form>
        
        <button id="btnMAX" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MAX" value="SubmitLEDMAX" >2</button>
        <button id="btnMEDIUM" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MEDIUM" value="SubmitLEDMEDIUM" >1</button>
        <button id="btnMIN" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MIN" value="SubmitLEDMIN" >0</button>
        
        <!-- Controlling color -->
        <form action="updateDBLED.php" method="post" id="LED_RED">
        <br/>
            <input type="hidden" name="Color" value="R"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_GREEN">
            <input type="hidden" name="Color" value="G"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_BLUE">
            <input type="hidden" name="Color" value="B"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_ALL">
            <input type="hidden" name="Color" value="A"/>
        </form>
        <button id="btnRED" class="w3-button w3-circle w3-red" name= "subject" type="submit" form="LED_RED" value="SubmitLEDRED" >r</button>
        <button id="btnGREEN" class="w3-button w3-circle w3-green" name= "subject" type="submit" form="LED_GREEN" value="SubmitLEDGREEN" >g</button>
        <button id="btnBLUE" class="w3-button w3-circle w3-blue" name= "subject" type="submit" form="LED_BLUE" value="SubmitLEDBLUE" >b</button>
        <button id="btnALL" class="w3-button w3-circle w3-yellow" name= "subject" type="submit" form="LED_ALL" value="SubmitLEDALL" >a</button>

        <h4 id="ledstatus" >Loading ...</h4>
        <p id="getLEDStatus" hidden></p>
    </div>
    <br/>
    <a href="http://localhost:8000/shell.php" class="w3-button w3-round-xlarge w3-teal">Wakeup assistant</a>
    <script>
        var myVar = setInterval(myTimer, 100);
        function myTimer() {
        var getLEDStat = document.getElementById("getLEDStatus").innerHTML;
        var LEDStatus = getLEDStat;
        if (LEDStatus == 100) {
            document.getElementById("ledstatus").innerHTML = "Power: 100 %";
        }
        if (LEDStatus == 50) {
            document.getElementById("ledstatus").innerHTML = "Power: 50 %";
        }
        if (LEDStatus == 0) {
            document.getElementById("ledstatus").innerHTML = "Power: 0 %";
        }
        if (LEDStatus == "") {
            document.getElementById("ledstatus").innerHTML = "Loading ...";
        }
        }
    </script>
    <h2>Sensor</h2>
    <h4>Temperature: <?=$sensor['temp']?> &#176C</h4>
    <h4>Humidity: <?=$sensor['humi']?> %</h4>
    
    <h2>Warning</h2>
    <?php 
    if ($sensor['pir']==1) echo '<h4>' . 'Có trộm </h4>';
    else echo '<h4>' . 'Không có trộm </h4>';
    if ($sensor['flame']==0) echo '<h4>' . 'Có cháy </h4>';
    else echo '<h4>' . 'Không có cháy </h4>';
    ?>
    </body>
    </html>
    <?php 
    }
else {
    header("location: log_out.php");
}