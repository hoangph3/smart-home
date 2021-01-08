<?php require_once 'database.php';
	//Feedback status led
	$getLEDStatusFromNodeMCU=$_POST["getLEDStatusFromNodeMCU"];
	$Write="<?php $" . "getLEDStatusFromNodeMCU='" . $getLEDStatusFromNodeMCU . "'; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
	echo $getLEDStatusFromNodeMCU;
	file_put_contents('LEDStatContainer.php',$Write);

	//Read sensor
	$temp = $_POST["temp"];
	echo $temp;
	$humi = $_POST["humi"];
	echo $humi;
	$pir = $_POST["pir"];
	echo $pir;
	$flame = $_POST["flame"];
	echo $flame;
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE sensor SET temp = ?, humi = ?, pir = ?, flame = ? WHERE ID = 0";
    $q = $pdo->prepare($sql);
    $q->execute(array($temp,$humi,$pir,$flame));
    Database::disconnect();
?>