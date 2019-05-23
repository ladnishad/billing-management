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


if (isset($_POST['submitCommon'])) {
	$ItemName = (isset($_POST['itemlistText']) ? $_POST['itemlistText'] : null);
	$NameItem = $_POST['itemlistText'];
	$ItemQuantity = $_POST['quantity'];

	$date = date('m/d/y');
	function convert($date)
	{
	  return str_replace("/", "", $date);
	}

	$cleanDate = convert($date);



	$commGetTotalCostQuery = "SELECT ItemCost FROM bills_management.items where ItemName = '$ItemName'";
	$commGetTotalCost = mysqli_query($conn,$commGetTotalCostQuery);

	if (!$commGetTotalCost) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

	$TotalCost = 0.0000;
	while ($TotalCostArray = mysqli_fetch_array($commGetTotalCost)){
		$TotalCost = $TotalCostArray['ItemCost'];
	}

	$commIndCost = ($TotalCost*$ItemQuantity)/4;
	$CommQuery = "INSERT INTO bills_management.bills (`BillsID`, `BillItem`, `HarshitCost`, `HarishCost`, `DeepCost`, `NishadCost`, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$commIndCost', '$commIndCost', '$commIndCost', '$commIndCost', '$ItemQuantity', '$TotalCost');";
	$execCommQuery = mysqli_query($conn,$CommQuery);
	if (!$execCommQuery) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}
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
				var check = document.getElementById('checkbDivide');
        var num = parseInt(text.value);

        for (var i = 0; i < num; i++) {
          var optionArray = new Array();
          <?php foreach($names as $val){ ?>
            optionArray.push('<?php echo $val; ?>');
            <?php } ?>

						var selectList = document.createElement("select");
						selectList.setAttribute("id"+i, "mySelect"+i);
						itemTypePrivate.appendChild(selectList);

						if (!check.checked) {
							var qtyLabel = document.createElement("label");
							qtyLabel.id = "qtyLabel"+i;
							qtyLabel.innerHTML = "Enter Quantity: ";
							itemTypePrivate.appendChild(qtyLabel);
							var qtyText = document.createElement("input");
							qtyText.Type = "text";
							qtyText.id = "qtyText"+i;
							qtyText.class = "inputs";
							qtyText.placeholder = "Ex: 1,2,3,...";
							itemTypePrivate.appendChild(qtyText);
							linebreak = document.createElement("br");
							itemTypePrivate.appendChild(linebreak);
						}
						else {
							linebreak = document.createElement("br");
							itemTypePrivate.appendChild(linebreak);
						}

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


		<label for="itemType">Item Type: </label>
		<select  name="itemtype" onchange="changeFunc(value);">
			<option value="0">Select one</option>
			<option value="1">Personal</option>
			<option value="2">Common</option>
		</select>

		<div id="itemTypeCommon" style="display: none">
			<form action="index.php" method="post">
				<label for="selectItem">Item Name: </label>
		  	<?php
		  	$selectItemName = "SELECT ItemName FROM items";
		  	$selectItemNameresult = mysqli_query($conn, $selectItemName);

		  	echo "<input type='text' name = 'itemlistText' id='itemlistText' list='itemlist'>";
		  	echo "<datalist id='itemlist' name='itemList'>";
		  	while ($selectItemNameresultrow = mysqli_fetch_array($selectItemNameresult)) {
		    	echo "<option>".$selectItemNameresultrow{'ItemName'}."</option>";
		  	}
		  	echo "</datalist>";
		  	?><br>
			<label for="itemQuantity">Select quantity: </label>
			<input type="text" id="quantity" name="quantity" placeholder="Ex: 2,3,4,..." class="inputs"><br>
			<input type="submit" name="submitCommon" value="Add to bill" class="button"><br>
		</form>
		</div>

		<div id="itemTypePrivate" style="display: none">
			<label for="NumUsersQuant">Total Quantity:  </label>
			<input type="text" id = "usersQuant" name="usersQuant" placeholder="Ex: 1,2,3,4,..." class="inputs"><br>

			<label for="NumUsers">Number of people:  </label>
			<input type="text" id = "users" name="users" placeholder="Ex: 1,2,3,4,..." class="inputs">
			<input type="submit" name="addusers" onclick="addElement()"value="Add Users" id="addusers" class="button">

			<input type="checkbox" id="checkbDivide" cname="equalDivide" value="divide"> Divide Equally<br>
			<label for="userselect" id="userselect" style="display: none">Select User(s): </label>

			<input type="submit" name="submitPrivate" value="Add to bill" class="button"><br>
			</form>
		</div>

		<table class="center">
		    <thead>
		    <tr>
		    			<th scope="col">Bill#</th>
		          <th scope="col" colspan="2">Item</th>
		          <th scope="col">Harshit</th>
							<th scope="col">Harish</th>
							<th scope="col">Deep</th>
							<th scope="col">Nishad</th>
							<th scope="col">Qty</th>
							<th scope="col">Total Cost</th>
		      </tr>
		      </thead>
		      <tbody>
		      <?php
		      $retrieveBill = "SELECT BillsID, BillItem, HarshitCost, HarishCost,DeepCost,NishadCost,TotalQty,TotalCost FROM Bills";
		      $billsresult = mysqli_query($conn,$retrieveBill);
		      $num_rows = mysqli_num_rows($billsresult);

		        while ($row = mysqli_fetch_array($billsresult)):; ?>
		        <tr>
		          <td class="item-id"><?php echo $row['BillsID'];?></td>
		          <td colspan="2"><strong class="item-name"><?php echo $row['BillItem'];?></strong></td>
		          <td class="item-price"><?php echo "$".$row['HarshitCost'];?></td>
							<td class="item-price"><?php echo "$".$row['HarishCost'];?></td>
							<td class="item-price"><?php echo "$".$row['DeepCost'];?></td>
							<td class="item-price"><?php echo "$".$row['NishadCost'];?></td>
							<td class="item-price"><?php echo $row['TotalQty'];?></td>
							<td class="item-price"><?php echo "$".$row['TotalCost'];?></td>
		        </tr>
		      <?php endwhile; ?>
		      </tbody>
		    </table>




	</body>
</html>
