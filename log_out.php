<?php require_once 'utils.php';
$conn = connect_db();
$sql = "UPDATE statusled SET Stat = 0 WHERE ID = 0";
execute($sql);
$sql = "UPDATE user SET auth = 0 WHERE ID = 0";
execute($sql);
header("location: index.php");
