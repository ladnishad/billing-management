<?php

$servername = "localhost";
$username = "root";
$pwd = "";
$dbname = "bills_management";

$conn = new mysqli($servername,$username,$pwd,$dbname);
if($conn->connect_error){
	die("Connection failed. Issue : ".$conn->connect_error);
}
?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    <title>WELCOME</title>
  </head>
  <body>
    <h1>WELCOME</h1>


    <div class="btn_center">
    <a href="../php/Items.php" class="button" >View Items</a>
  </div>

<br><br>
<label for="selectItem">Item Name: </label>
  <?php
  $sql = "SELECT ItemName FROM items";
  $result = mysqli_query($conn, $sql);

  echo "<input list='itemlist' type='text'>";
  echo "<datalist id='itemlist'>";
  while ($row = mysqli_fetch_array($result)) {

    echo "<option>".$row{'ItemName'}."</option>";

  }
  echo "</datalist>";
  ?>
<label for="itemQuantity">Select quantity: </label>
  <input type="text" name="quantity" placeholder="Ex: 2,3,4,..." class="inputs">



  </body>
</html>
