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
<title>View/Add/Update Items</title>
</head>
<body>
<a href="index.php" class="button" >Back</a>
<h1>View/Add/Update Items</h1>


<table class="center">
    <thead>
    <tr>
    <th scope="col">Id</th>
          <th scope="col" colspan="2">Item</th>
          <th scope="col">Price</th>
          <th scope="col" colspan= "2">Company/Keywords</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $sql = "SELECT ItemID, ItemName, ItemCost, ItemComm FROM Items";
      $result = mysqli_query($conn,$sql);
      $num_rows = mysqli_num_rows($result);

        while ($row = mysqli_fetch_array($result)):; ?>
        <tr>
          <td class="item-id"><?php echo $row['ItemID'];?></td>
          <td colspan="2"><strong class="item-name"><?php echo $row['ItemName'];?></strong></td>
          <td class="item-price"><?php echo "$".$row['ItemCost'];?></td>
          <td colspan="2" class="item-extra"><?php echo $row['ItemComm'];?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <div class="btn_center">
    <button onclick="revealAdd()" class="button">Add Item</button>
    <input type="button" class="button" value="Update Item">
  </div>
  <div id="addForm" class="formDiv" style="display: none">
    <form action="addItem.php" method="post">
    <label for="ItemName">Enter Item Name</label>
    <input type="text" id="itemname" name="itemname" placeholder="Eg: Eggs, Milk,.." required>

    <label for="ItemCost">Enter Cost of the item</label>
    <input type="text" id="itemcost" name="itemcost" placeholder="Eg: 20" required>

    <label for="Comments">Comments/Keywords</label>
    <input type="text" id="itemcom" name="itemcom" placeholder="Brand name/Extra details"><br>
    <input type="submit" name="submit" value="Add" class="button">
  </form>

  </div>

  <script>
    function revealAdd(){
      var div = document.getElementById("addForm");
      if (div.style.display === "none") {
        div.style.display = "block";
      } else {
        div.style.display = "none";
      }
    }
  </script>

  </body>
</html>
