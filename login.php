<?php require_once 'utils.php';
if (isset($_POST['dangnhap'])) 
{
    $conn = connect_db();
    $s_username = addslashes($_POST['username']);
    $s_userpwd = addslashes($_POST['userpwd']);
     
	$sql = " select * from user where username = '$s_username' and userpwd = '$s_userpwd' ";
    $query = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($query);     

    if (mysqli_num_rows($query) > 0) {
        $sql = "UPDATE statusled SET Stat = 100 WHERE ID = 0";
        execute($sql);
        $sql = "UPDATE user SET auth = 1 WHERE ID = 0";
        execute($sql);
        header("location: Main.php");
    }
	else {
		header("location: login.php");
	}
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
