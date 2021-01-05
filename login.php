<?php require_once 'database.php';
if (isset($_POST['dangnhap'])) 
{
    $username = addslashes($_POST['username']);
    $userpwd = addslashes($_POST['userpwd']);

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM user WHERE username = ? AND userpwd = ?';
    $q = $pdo->prepare($sql);
    $q->execute(array($username, $userpwd));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    if (!empty($data)) {
        $sql = "UPDATE statusled SET Stat = 100 WHERE ID = 0";
        $q = $pdo->prepare($sql);
        $q->execute();
        $sql = "UPDATE user SET auth = 1 WHERE ID = 0";
        $q = $pdo->prepare($sql);
        $q->execute();
        header("location: Main.php");
    }
	else {
		header("location: login.php");
    }
    Database::disconnect();
}
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/login.css"/>
    </head>
    <body>
        <header>
            <img src="css/logo.png" width="90px" height="90px"><br/><br/>
            <h1>Sign in to SmartHome</h1>
        </header>
        <form method="post">
		<div id="loginbox">
            <label id="username">
                <p>Username</p>
                <input required="true" type="text" id="username" name="username">
            </label>
            <label id="userpwd">
                <p>Password <a href="#">Forgot Password?</a></p>
                <input required="true" type="password" id="userpwd" name="userpwd">
            </label>
            <input type="submit" name="dangnhap" value="Sign in">
            <div id="new">
                <p>New to SmartHome? <a href="#">Create an account.</a></p>
            </div>
            <div id="list">
                <ul>
                    <li class="m3"><a href="#">Terms</a></li>
                    <li class="m3"><a href="#">Privacy</a></li>
                    <li class="m3"><a href="#">Security</a></li>
                    <li class="m3"><a href="#" id="contact">Contact</a></li>
                </ul>
            </div>
        </div>  
		</form>
    </body>
</html>
