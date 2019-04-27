<?php

$servername = "localhost";
$username = "root";
$pwd = "";
$dbname = "bills_management";

$conn = new mysqli($servername,$username,$pwd,$dbname);
if($conn->connect_error){
	die("Connection failed. Issue : ".$conn->connect_error);
}

$getUsername = "SELECT username from users";
$getUsernamequery = mysqli_query($conn,$getUsername);
while($getUsernamerow = mysqli_fetch_array($getUsernamequery)){
		$names[] = $getUsernamerow['username'];
}


?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
		<script type="text/javascript">

			function changeFunc(value){
				var divCommon = document.getElementById("itemTypeCommon");
				var divPrivate = document.getElementById("itemTypePrivate");
				if (value == "0") {
					divPrivate.style.display = "none";
	        divCommon.style.display = "none";
				}
	      else if (value == "1") {
					divPrivate.style.display = "block";
	        divCommon.style.display = "none";
	      } else {
	        divCommon.style.display = "block";
					divPrivate.style.display = "none";
	      }
			}

			function addElement(){
				document.getElementById("userselect").style.display = "inline";
        var text = document.getElementById('users');
        var num = parseInt(text.value);

        for (var i = 0; i < num; i++) {
          var optionArray = new Array();
          <?php foreach($names as $val){ ?>
            optionArray.push('<?php echo $val; ?>');
            <?php } ?>

          	var selectList = document.createElement("select");
          	selectList.setAttribute("id"+i, "mySelect"+i);
          	itemTypePrivate.appendChild(selectList);

          	for (var j = 0; j < optionArray.length; j++) {
            	var option = document.createElement("option");
            	option.setAttribute("value",optionArray[j]);
            	option.text = optionArray[j];
            	selectList.appendChild(option);
          	}
        	}
      	}

			function getValue(){
        var checks = document.getElementsByClassName('checks');
        var str = '';
        let num = 0;

        for (var i = 0; i < 4; i++) {
          if (checks[i].checked === true) {
            num += parseInt(checks[i].value);
          }
        }
        alert(num);
        console.log(num);
      }
		</script>
    <title>WELCOME</title>
  </head>
  <body>
    <h1>WELCOME</h1>


    <div class="btn_center">
    <a href="../php/Items.php" class="button" >View Items</a>
		<a href="../php/Items.php" class="button" >View Past Bills</a>
  	</div>

		<br><br>

		<label for="selectItem">Item Name: </label>
  	<?php
  	$selectItemName = "SELECT ItemName FROM items";
  	$selectItemNameresult = mysqli_query($conn, $selectItemName);

  	echo "<input list='itemlist' type='text'>";
  	echo "<datalist id='itemlist'>";
  	while ($selectItemNameresultrow = mysqli_fetch_array($selectItemNameresult)) {
    	echo "<option>".$selectItemNameresultrow{'ItemName'}."</option>";
  	}
  	echo "</datalist>";
  	?>

		<label for="itemType">Item Type: </label>
		<select  name="itemtype" onchange="changeFunc(value);">
			<option value="0">Select one</option>
			<option value="1">Personal</option>
			<option value="2">Common</option>
		</select>

		<div id="itemTypeCommon" style="display: none">
			<label for="itemQuantity">Select quantity: </label>
			<input type="text" name="quantity" placeholder="Ex: 2,3,4,..." class="inputs"><br>
		</div>

		<div id="itemTypePrivate" style="display: none">
			<label for="NumUsers">Number of people:  </label>
			<input type="text" id = "users" name="users" placeholder="Ex: 1,2,3,4,..." class="inputs">
			<input type="submit" name="addusers" onclick="addElement()"value="Add Users" id="addusers" class="button"><br>
			<label for="userselect" id="userselect" style="display: none">Select User(s): </label>
		</div>


  	<input type="submit" name="submit" value="Add to bill" class="button"><br>
  	</div>
	</body>
</html>
