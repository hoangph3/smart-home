<?php 
$command = escapeshellcmd('python3 asr.py');
$output = exec($command);
$result = strtolower($output);
if ($result=='bật đèn đỏ' || $result=='đèn đỏ lên'){
    $Write="<?php $" . "Color='" . 'R' . "'; ";
    file_put_contents('../SetData.php', $Write);
}
elseif ($result=='bật đèn xanh' || $result=='đèn xanh lên'){
    $Write="<?php $" . "Color='" . 'G' . "'; ";
    file_put_contents('../SetData.php', $Write);
}
elseif ($result=='bảy sắc cầu vồng') {
    $Write="<?php $" . "Color='" . 'A' . "'; ";
    file_put_contents('../SetData.php', $Write);
};
header("location: http://localhost/Smart_Home/Main.php");
?>