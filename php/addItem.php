<?php

$servername = "";
$username = "";
$pwd = "";
$dbname = "";

$conn = new mysqli($servername,$username,$pwd,$dbname);
if($conn->connect_error){
	die("Connection failed. Issue : ".$conn->connect_error);
}


if (isset($_POST['submit']))
{
	$ItemName = trim($_POST['itemname']);
	$ItemCost = trim($_POST['itemcost']);
	$Comments = trim($_POST['itemcom']);

	$sql = "INSERT INTO Items (ItemID,ItemName,ItemCost,ItemComm) VALUES (NULL,'{$ItemName}','$ItemCost','$Comments')";
  $insert = $conn->query($sql);
  if ($insert)
  {
    $message = "Item added.";
    header("refresh:0.5; url=Items.php");
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
  else
  {
    $message1 = "Couldn't add.";
    header("refresh:0.5; url=Items.php");
    echo "<script type='text/javascript'>alert('$message1');</script>";
  }

}

  $conn->close();



?>
