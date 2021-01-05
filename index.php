<?php require_once 'database.php';
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT * FROM user WHERE ID = 0';
$q = $pdo->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Security Camera</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/webcam.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
</head>
<body>
<br/>
<div class="container">
    <h1 class="text-center">Security Camera</h1>
    <form method="POST" action="authentication.php">
        <div class="row">
            <div class="col-md-6 text-center">
                <input type=button class="btn btn-info" value="Shoot" onclick="take_snapshot()">
                <br/><br/>
                <div id="my_camera"></div>
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6 text-center">
                <button class="btn btn-success">Submit</button>
                <br/><br/>
                <div id="results"></div>
            </div>
        </div>
    </form>
    <div class="col-md-12 text-center">
        <br/>
        <?php 
        if ($data['auth']==1) { ?>
            <button class="btn btn-warning btn-lg" onclick="window.open('Main.php','_self')">Join to Smart Home</button>
        <?php } ?>
    </div>
</div>
  
<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
    Webcam.set({
        width: 550,
        height: 400,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
  
    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>
</body>
</html>
