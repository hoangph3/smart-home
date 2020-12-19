<?php
	$getLEDStatusFromNodeMCU=$_POST["getLEDStatusFromNodeMCU"];
	$Write="<?php $" . "getLEDStatusFromNodeMCU='" . $getLEDStatusFromNodeMCU . "'; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
    echo $getLEDStatusFromNodeMCU;
	file_put_contents('LEDStatContainer.php',$Write);
?>