<?php require_once 'utils.php';
session_start();
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
$conn = connect_db();
$sql = "UPDATE statusled SET Stat = 0 WHERE ID = 0";
execute($sql);
$sql = "UPDATE user SET auth = 0 WHERE ID = 0";
execute($sql);
header("location: index.php");
