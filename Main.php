<?php require_once 'database.php';
require_once 'SetData.php';
require_once 'SetDataFan.php';
session_start();
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

    $sql = "UPDATE statusfan SET Stat = ? WHERE ID = 0";
    $q = $pdo->prepare($sql);
    $q->execute(array($StatFan));
    
    //Read sensor
    $sql = 'SELECT * FROM sensor WHERE ID = 0';
    $q = $pdo->prepare($sql);
    $q->execute();
    $sensor = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
?>
<!DOCTYPE html>
<html>
<title>Smart Home</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<script src="js/fire.js"></script>
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>Menu</button>
  <span class="w3-bar-item w3-right">Smart Home</span>  
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="css/avatar2.jpg" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong><?=$data['username']?></strong></span><br>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="#" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-home fa-fw"></i>  Home</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  Views</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-camera fa-fw"></i>  Camera</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  News</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  History</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  Settings</a>
    <a href="log_out.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sign out</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-thermometer w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3><?=$sensor['temp']?> &#176C</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Temperature</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-tint w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3><?=$sensor['humi']?> %</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Humidity</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-fire w3-xxxlarge"></i></div>
        <?php if($sensor["flame"]==1){
          echo '<div class="w3-right">
                  <h3>0</h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Not Fire</h4>';
        }else{
          echo '<div class="w3-right">
                  <h3>1</h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Fire</h4>';
        }
        ?>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-exclamation-triangle w3-xxxlarge"></i></div>
        <?php if($sensor["pir"]==1){
          echo '<div class="w3-right">
                  <h3>1</h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Danger</h4>';
        }else{
          echo '<div class="w3-right">
                  <h3>0</h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Safe</h4>';
        }
        ?>
      </div>
    </div>
  </div>
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-third">
        </br>
        <h5>New Year</h5>
        <img src="css/region.gif" style="width:100%" alt="Google Regional Map">
      </div>
      <div class="w3-twothird">
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

        <!-- Controlling color -->
        <form action="updateDBLED.php" method="post" id="LED_RED">
        <br/>
            <input type="hidden" name="Color" value="R"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_GREEN">
            <input type="hidden" name="Color" value="G"/>
        </form>
        <!-- <form action="updateDBLED.php" method="post" id="LED_BLUE">
            <input type="hidden" name="Color" value="B"/>
        </form>
        <form action="updateDBLED.php" method="post" id="LED_ALL">
            <input type="hidden" name="Color" value="A"/>
        </form> -->

        <!-- Controlling Fan -->
        <form action="updateDBFAN.php" method="post" id="FAN_MAX">
            <input type="hidden" name="StatFan" value="100"/>    
        </form>
        <form action="updateDBFAN.php" method="post" id="FAN_MEDIUM">
            <input type="hidden" name="StatFan" value="50"/>    
        </form>
        <form action="updateDBFAN.php" method="post" id="FAN_MIN">
            <input type="hidden" name="StatFan" value="0"/>
        </form>

        <h5>System</h5>
        <table class="w3-table w3-striped w3-white">
          <tr>
            <td><i class="fa fa-lightbulb-o w3-text-blue w3-large"></i></td>
            <td>Light level</td>
            <td>
            <button id="btnMAX" name= "subject" type="submit" form="LED_MAX" value="SubmitLEDMAX" >2</button>
            <button id="btnMEDIUM" name= "subject" type="submit" form="LED_MEDIUM" value="SubmitLEDMEDIUM" >1</button>
            <button id="btnMIN" name= "subject" type="submit" form="LED_MIN" value="SubmitLEDMIN" >0</button>
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-dashboard w3-text-yellow w3-large"></i></td>
            <td>Light color</td>
            <td>
            <button id="btnRED" name= "subject" type="submit" form="LED_RED" value="SubmitLEDRED" >red</button>
            <button id="btnGREEN" name= "subject" type="submit" form="LED_GREEN" value="SubmitLEDGREEN" >green</button>
            <!-- <button id="btnBLUE" name= "subject" type="submit" form="LED_BLUE" value="SubmitLEDBLUE" >b</button>
            <button id="btnALL" name= "subject" type="submit" form="LED_ALL" value="SubmitLEDALL" >a</button> -->
            </td>
          </tr>
          <tr>
            <td><i class="fas fa-fan w3-text-red w3-large"></i></td>
            <td>Fan level</td>
            <td>
            <button id="fanMAX" name= "subject" type="submit" form="FAN_MAX" value="SubmitFANMAX" >2</button>
            <button id="fanMEDIUM" name= "subject" type="submit" form="FAN_MEDIUM"value="SubmitFANMEDIUM" >1</button>
            <button id="fanMIN" name= "subject" type="submit" form="FAN_MIN" value="SubmitFANMIN" >0</button>
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-thermometer w3-text-orange w3-large"></i></td>
            <td>Heating system</td>
            <td>
            ...
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-microphone w3-text-green w3-large"></i></td>
            <td>Home assistant</td>
            <td>
            <button onclick="window.open('http:\/\/localhost:8000\/shell.php', '_self')" class="fa fa-microphone"></button>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Wheather Forecast</h5>
    <a class="weatherwidget-io" href="https://forecast7.com/en/21d00105d82/hanoi/" data-label_1="HANOI" data-label_2="WEATHER" data-theme="original" >HANOI WEATHER</a>
    <script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
    </script>
    <!-- <p>New Visitors</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-green" style="width:25%">+25%</div>
    </div>

    <p>New Users</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-orange" style="width:50%">50%</div>
    </div>

    <p>Bounce Rate</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-red" style="width:75%">75%</div>
    </div> -->
  </div>
  <hr>
  <div class="w3-container">
    <h5>Team</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <td>Phạm Hoàng</td>
        <td>20170761</td>
      </tr>
      <tr>
        <td>Dương Đức Hiếu</td>
        <td>20172547</td>
      </tr>
      <tr>
        <td>Trần Việt Hoàng</td>
        <td>20172571</td>
      </tr>
    </table><br>
    <button class="w3-button w3-dark-grey">More infomations <i class="fa fa-arrow-right"></i></button>
  </div>
  <hr>
  <!-- <div class="w3-container">
    <h5>Recent Users</h5>
    <ul class="w3-ul w3-card-4 w3-white">
      <li class="w3-padding-16">
        <img src="css/avatar2.png" class="w3-left w3-circle w3-margin-right" style="width:35px">
        <span class="w3-xlarge">Mike</span><br>
      </li>
      <li class="w3-padding-16">
        <img src="css/avatar5.png" class="w3-left w3-circle w3-margin-right" style="width:35px">
        <span class="w3-xlarge">Jill</span><br>
      </li>
      <li class="w3-padding-16">
        <img src="css/avatar6.png" class="w3-left w3-circle w3-margin-right" style="width:35px">
        <span class="w3-xlarge">Jane</span><br>
      </li>
    </ul>
  </div>
  <hr>

  <div class="w3-container">
    <h5>Feedback</h5>
    <div class="w3-row">
      <div class="w3-col m2 text-center">
        <img class="w3-circle" src="css/avatar3.png" style="width:96px;height:96px">
      </div>
      <div class="w3-col m10 w3-container">
        <h4>John <span class="w3-opacity w3-medium">Sep 29, 2014, 9:12 PM</span></h4>
        <p>Keep up the GREAT work! I am cheering for you!! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><br>
      </div>
    </div>

    <div class="w3-row">
      <div class="w3-col m2 text-center">
        <img class="w3-circle" src="css/avatar1.png" style="width:96px;height:96px">
      </div>
      <div class="w3-col m10 w3-container">
        <h4>Bo <span class="w3-opacity w3-medium">Sep 28, 2014, 10:15 PM</span></h4>
        <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><br>
      </div>
    </div>
  </div>
  <br>
  <div class="w3-container w3-white w3-padding-32">
    <div class="w3-row">
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-green">Demographic</h5>
        <p>Language</p>
        <p>Country</p>
        <p>City</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-red">System</h5>
        <p>Browser</p>
        <p>OS</p>
        <p>More</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-orange">Target</h5>
        <p>Users</p>
        <p>Active</p>
        <p>Geo</p>
        <p>Interests</p>
      </div>
    </div>
  </div> -->

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <h4>CONTACT</h4>
    <p>Powered by <a href="https://github.com/hoangph3" target="_blank">hoangph3</a> - Phone: 0339362666</p>
  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
</script>

</body>
</html>
<?php 
    }
else {
    header("location: log_out.php");
}