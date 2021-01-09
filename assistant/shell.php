<?php 
$command = escapeshellcmd('python3 asr.py');
$result = exec($command);
echo $result;

//Check stat
if ($result==1){
    $Write="<?php $" . "Stat=" . 100 . "; $" . "Color='G" . "'; ";
    file_put_contents('../SetData.php', $Write);
}
elseif ($result==0){
    $Write="<?php $" . "Stat=" . 0 . "; $" . "Color='G" . "'; ";
    file_put_contents('../SetData.php', $Write);
}

//Check color
if ($result=='r'){
    $Write="<?php $" . "Stat=" . 100 . "; $" . "Color='R" . "'; ";
    file_put_contents('../SetData.php', $Write);
}
elseif ($result=='g'){
    $Write="<?php $" . "Stat=" . 100 . "; $" . "Color='G" . "'; ";
    file_put_contents('../SetData.php', $Write);
}

//Check fan
if ($result=='max'){
    $Write="<?php $" . "StatFan=" . 100 . "; ";
    file_put_contents('../SetDataFan.php', $Write);
}
elseif ($result=='min'){
    $Write="<?php $" . "StatFan=" . 0 . "; ";
    file_put_contents('../SetDataFan.php', $Write);
}
elseif ($result=='medium'){
    $Write="<?php $" . "StatFan=" . 50 . "; ";
    file_put_contents('../SetDataFan.php', $Write);
}
header("location: http://localhost/Smart_Home/Main.php");
?>