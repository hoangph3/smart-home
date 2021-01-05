<?php require_once 'database.php';
// Check login or not
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT * FROM user WHERE ID = 0';
$q = $pdo->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
if ($data['auth'] == 1) {
    $Write="<?php $" . "getLEDStatusFromNodeMCU=''; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
    file_put_contents('LEDStatContainer.php',$Write);
    $setLEDStatusFromServer = file_get_contents('SetData.php'); ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Smart Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <script src="js/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#getLEDStatus").load("LEDStatContainer.php");
                setInterval(function() {
                $("#getLEDStatus").load("LEDStatContainer.php");
                }, 500);
            });
        </script>
    </head>
    <body>
    <div class="w3-container" align="left">
        <h1 style="display: inline-block;"> Light control </h1>
        <a style="float: right; font-size: 24px; font-weight: bold;" href="log_out.php">Log out</a>
        <!-- Controlling voltage -->
        <form action="updateDBLED.php" method="post" id="LED_MAX">
            <input type="hidden" name="Stat" value="100"/>    
        </form>
        <form action="updateDBLED.php" method="post" id="LED_MEDIUM">
            <input type="hidden" name="Stat" value="67"/>    
        </form>
        <form action="updateDBLED.php" method="post" id="LED_MIN">
            <input type="hidden" name="Stat" value="33"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_OFF">
            <input type="hidden" name="Stat" value="0"/>
        </form>
        
        <button id="btnMAX" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MAX" value="SubmitLEDMAX" >3</button>
        <button id="btnMEDIUM" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MEDIUM" value="SubmitLEDMEDIUM" >2</button>
        <button id="btnMIN" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_MIN" value="SubmitLEDMIN" >1</button>
        <button id="btnOFF" class="w3-button w3-circle w3-teal" name= "subject" type="submit" form="LED_OFF" value="SubmitLEDOFF">0</button>
        
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

        <?php 
        // if ($setLEDStatusFromServer == 1)
        //     echo '<script>function clickOn(){document.getElementById("btnON").click();}
        //                     setInterval(clickOn, 500);
        //         </script>';
        // else echo '<script>function clickOff(){document.getElementById("btnOFF").click();}
        //                     setInterval(clickOff, 500); 
        //         </script>';
        // ?>

        <h4 id="ledstatus" >Loading ...</h4>
        <p id="getLEDStatus" hidden></p>
    </div>
    <script>
        var myVar = setInterval(myTimer, 500);
        function myTimer() {
        var getLEDStat = document.getElementById("getLEDStatus").innerHTML;
        var LEDStatus = getLEDStat;
        if (LEDStatus == 100) {
            document.getElementById("ledstatus").innerHTML = "Power: 100 %";
        }
        if (LEDStatus == 67) {
            document.getElementById("ledstatus").innerHTML = "Power: 67 %";
        }
        if (LEDStatus == 33) {
            document.getElementById("ledstatus").innerHTML = "Power: 33 %";
        }
        if (LEDStatus == 0) {
            document.getElementById("ledstatus").innerHTML = "Power: 0 %";
        }
        if (LEDStatus == "") {
            document.getElementById("ledstatus").innerHTML = "Loading ...";
        }
        }
    </script>  
    </body>
    </html>
    <?php }
else {
    header("location: log_out.php");
}