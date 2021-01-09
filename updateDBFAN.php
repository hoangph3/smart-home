<?php require_once 'database.php';
  if (!empty($_POST)) {
    //Retrived data 
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM statusfan WHERE ID = 0';
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetch(PDO::FETCH_ASSOC);
    
    //Read Stat and Color
    $StatFan = isset($_POST['StatFan']) ? $_POST['StatFan'] : $data['Stat'];

    //Insert data
    $sql = "UPDATE statusfan SET Stat = ? WHERE ID = 0";
    $q = $pdo->prepare($sql);
    $q->execute(array($StatFan));
    Database::disconnect();

    $Write="<?php $" . "StatFan=" . $StatFan . "; ";
    file_put_contents('SetDataFan.php', $Write);

    header("Location: Main.php");
  }
?>