<?php require_once 'database.php';
$id=0; 
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT * FROM user WHERE ID = ?';
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
if ($data['auth'] == 1) {
    $Write="<?php $" . "getLEDStatusFromNodeMCU=''; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
    file_put_contents('LEDStatContainer.php',$Write);
    $setLEDStatusFromServer = file_get_contents('SetData.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>VCS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/demo.css">
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
<h1 class="text-center">Welcome to Smart Home</h1>
<div class="w3-container" align="left">
    <h1 style="display: inline-block;"> Light control </h1>
    <a style="float: right; font-size: 24px; font-weight: bold;" href="log_out.php">Log out</a>
    <form action="updateDBLED.php" method="post" id="LED_ON">
        <input type="hidden" name="Stat" value="100"/>    
    </form>
    <form action="updateDBLED.php" method="post" id="LED_MEDIUM">
        <input type="hidden" name="Stat" value="50"/>    
    </form>
    <form action="updateDBLED.php" method="post" id="LED_OFF">
        <input type="hidden" name="Stat" value="0"/>
    </form>
    <button id="btnON" class="buttonON" name= "subject" type="submit" form="LED_ON" value="SubmitLEDON" >2</button>
    <button id="btnMEDIUM" class="buttonMEDIUM" name= "subject" type="submit" form="LED_MEDIUM" value="SubmitLEDMEDIUM" >1</button>
    <button id="btnOFF" class="buttonOFF" name= "subject" type="submit" form="LED_OFF" value="SubmitLEDOFF">0</button>  
    
    <?php 
    // if ($setLEDStatusFromServer == 1)
    //     echo '<script>function clickOn(){document.getElementById("btnON").click();}
    //                     setInterval(clickOn, 500);
    //         </script>';
    // else echo '<script>function clickOff(){document.getElementById("btnOFF").click();}
    //                     setInterval(clickOff, 500); 
    //         </script>';
    // ?>

    <h1 id="ledstatus" >Loading ...</h1>
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
</body>
</html>
<?php }
else {
    header("location: log_out.php");
}