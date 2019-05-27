<?php
$servername = "";
$username = "";
$pwd = "";
$dbname = "";

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
		</script>
    <title>WELCOME</title>
  </head>
  <body>
    <h1>WELCOME</h1>


    <div class="btn_center">
    <a href="../php/Items.php" class="button" >View Items</a>
		<a href="../php/ViewBills.php" class="button" >View Past Bills</a>
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

				$getUsername = "SELECT username from users";
				$getUsernamequery = mysqli_query($conn,$getUsername);
				while($getUsernamerow = mysqli_fetch_array($getUsernamequery)){
						$names[] = $getUsernamerow['username'];
				}

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
			<form id="personalform" action="index.php" method="post">
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
			<label for="NumUsersQuant">Total Quantity:  </label>
			<input type="text" id = "usersQuant" name="usersQuant" placeholder="Ex: 1,2,3,4,..." class="inputs"><br>

			<label for="NumUsers">Number of people:  </label>
			<input type="text" id = "users" name="users" placeholder="Ex: 1,2,3,4,..." class="inputs">
			<input type="button" name="addusers" onclick="addElement()"value="Add Users" id="addusers" class="button"><br>

			<script type="text/javascript">

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
						selectList.setAttribute("id", "mySelect"+i);
						selectList.setAttribute("name", "mySelect"+i);
						personalform.appendChild(selectList);

							var qtyLabel = document.createElement("label");
							qtyLabel.id = "qtyLabel"+i;
							qtyLabel.innerHTML = "Enter Quantity: ";
							personalform.appendChild(qtyLabel);
							var qtyText = document.createElement("input");
							qtyText.Type = "text";
							qtyText.id = "qtyText"+i;
							qtyText.name = "qtyText"+i;
							qtyText.class = "inputs";
							qtyText.placeholder = "Ex: 1,2,3,...";
							personalform.appendChild(qtyText);
							linebreak = document.createElement("br");
							personalform.appendChild(linebreak);

						for (var j = 0; j < optionArray.length; j++) {
							var option = document.createElement("option");
							option.setAttribute("value",optionArray[j]);
							option.text = optionArray[j];
							selectList.appendChild(option);
						}
					}
				}
			</script>

			<label for="userselect" id="userselect" style="display: none">Select User(s): </label>

			<input type="submit" name="submitPrivate" value="Add to bill" class="button"><br>


			</form>
		</div>

<?php



if (isset($_POST['deleteBill'])) {
	$clearBillsQuery = "DELETE FROM bills; ";
	$ExecclearBillsQuery = mysqli_query($conn,$clearBillsQuery);
	if (!$ExecclearBillsQuery) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}

if (isset($_POST['submitBill'])) {

	$TransferQuery = "INSERT INTO billslist (SELECT * FROM bills);";
	$ExecTransferQuery = mysqli_query($conn,$TransferQuery);

	if (!$ExecTransferQuery) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

	$clearBillsQuery = "DELETE FROM bills; ";
	$ExecclearBillsQuery = mysqli_query($conn,$clearBillsQuery);
	if (!$ExecclearBillsQuery) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}
if (isset($_POST['submitPrivate'])) {
	// code...
	$ItemName = (isset($_POST['itemlistText']) ? $_POST['itemlistText'] : null);
	$NameItem = $_POST['itemlistText'];
	$ItemQuantity = $_POST['usersQuant'];

	$date = date('m/d/y');
	function convert($date)
	{
	  return str_replace("/", "", $date);
	}

	$cleanDate = convert($date);

	$GetTotalCostQuery = "SELECT ItemCost FROM items where ItemName = '$ItemName'";
	$GetTotalCost = mysqli_query($conn,$GetTotalCostQuery);

	if (!$GetTotalCost) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

	$Cost = 0.0000;
	while ($TotalCostArray = mysqli_fetch_array($GetTotalCost)){
		$Cost = $TotalCostArray['ItemCost'];
	}

	$noOfUsers = $_POST['users'];

	if ($noOfUsers == '4') {
		$User1 = (isset($_POST['mySelect0']) ? $_POST['mySelect0'] : null).'Cost';
		$User2 = (isset($_POST['mySelect1']) ? $_POST['mySelect1'] : null).'Cost';
		$User3 = (isset($_POST['mySelect2']) ? $_POST['mySelect2'] : null).'Cost';
		$User4 = (isset($_POST['mySelect3']) ? $_POST['mySelect3'] : null).'Cost';


		$User1Qty =(isset($_POST['qtyText0']) ? $_POST['qtyText0'] : null);
		$User2Qty = (isset($_POST['qtyText1']) ? $_POST['qtyText1'] : null);
		$User3Qty = (isset($_POST['qtyText2']) ? $_POST['qtyText2'] : null);
		$User4Qty = (isset($_POST['qtyText3']) ? $_POST['qtyText3'] : null);

		$User1Cost = $Cost * $User1Qty;
		$User2Cost = $Cost * $User2Qty;
		$User3Cost = $Cost * $User3Qty;
		$User4Cost = $Cost * $User4Qty;

		$Total = $Cost * $ItemQuantity;

		$InsertQuery = "INSERT INTO bills (`BillsID`, `BillItem`, $User1, $User2, $User3, $User4, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$User1Cost', '$User2Cost', '$User3Cost', '$User4Cost', '$ItemQuantity', '$Total');";
		$execQuery = mysqli_query($conn,$InsertQuery);
		if (!$execQuery) {
	    printf("Error: %s\n", mysqli_error($conn));
	    exit();
	}


	}

	else if ($noOfUsers == '3') {
		$User1 = (isset($_POST['mySelect0']) ? $_POST['mySelect0'] : null).'Cost';
		$User2 = (isset($_POST['mySelect1']) ? $_POST['mySelect1'] : null).'Cost';
		$User3 = (isset($_POST['mySelect2']) ? $_POST['mySelect2'] : null).'Cost';

		$User1Qty =(isset($_POST['qtyText0']) ? $_POST['qtyText0'] : null);
		$User2Qty = (isset($_POST['qtyText1']) ? $_POST['qtyText1'] : null);
		$User3Qty = (isset($_POST['qtyText2']) ? $_POST['qtyText2'] : null);

		$User1Cost = $Cost * $User1Qty;
		$User2Cost = $Cost * $User2Qty;
		$User3Cost = $Cost * $User3Qty;

		$Total = $Cost * $ItemQuantity;

		$InsertQuery = "INSERT INTO bills (`BillsID`, `BillItem`, $User1, $User2, $User3, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$User1Cost', '$User2Cost', '$User3Cost', '$ItemQuantity', '$Total');";
		$execQuery = mysqli_query($conn,$InsertQuery);
		if (!$execQuery) {
	    printf("Error: %s\n", mysqli_error($conn));
	    exit();
	}

	}

	else if ($noOfUsers == '2') {
		$User1 = (isset($_POST['mySelect0']) ? $_POST['mySelect0'] : null).'Cost';
		$User2 = (isset($_POST['mySelect1']) ? $_POST['mySelect1'] : null).'Cost';

		$User1Qty =(isset($_POST['qtyText0']) ? $_POST['qtyText0'] : null);
		$User2Qty = (isset($_POST['qtyText1']) ? $_POST['qtyText1'] : null);

		$User1Cost = $Cost * $User1Qty;
		$User2Cost = $Cost * $User2Qty;

		$Total = $Cost * $ItemQuantity;

		$InsertQuery = "INSERT INTO bills (`BillsID`, `BillItem`, $User1, $User2, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$User1Cost', '$User2Cost', '$ItemQuantity', '$Total');";
		$execQuery = mysqli_query($conn,$InsertQuery);
		if (!$execQuery) {
	    printf("Error: %s\n", mysqli_error($conn));
	    exit();
	}

	}

	else if ($noOfUsers == '1') {
		$User1 = (isset($_POST['mySelect0']) ? $_POST['mySelect0'] : null).'Cost';

		$User1Qty =(isset($_POST['qtyText0']) ? $_POST['qtyText0'] : null);

		$User1Cost = $Cost * $User1Qty;

		$Total = $Cost * $ItemQuantity;

		$InsertQuery = "INSERT INTO bills (`BillsID`, `BillItem`, $User1, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$User1Cost', '$ItemQuantity', '$Total');";
		$execQuery = mysqli_query($conn,$InsertQuery);
		if (!$execQuery) {
	    printf("Error: %s\n", mysqli_error($conn));
	    exit();
	}

	}

	else{

	}
	?>

	<table class="center" style="table-layout:fixed;width:1000px;" id="Costs">
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
				$retrieveBill = "SELECT BillsID, BillItem, HarshitCost, HarishCost, DeepCost, NishadCost,TotalQty,TotalCost FROM Bills";
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

			<table class="center" style="table-layout:fixed;width:1000px;" id="Totals">
				<tbody>
					<?php
					$retrieveTotals = "SELECT SUM(HarshitCost) as HarshitTotal, SUM(HarishCost) as HarishTotal, SUM(DeepCost) as DeepTotal, SUM(NishadCost) as NishadTotal, SUM(TotalCost) as Total FROM Bills";
					$totalsresult = mysqli_query($conn,$retrieveTotals);
					$num_rows = mysqli_num_rows($totalsresult);

						while ($row = mysqli_fetch_array($totalsresult)):; ?>
					<tr>
						<td colspan="3"><strong class="item-name"><?php echo "Total";?></strong></td>
						<td class="item-price"><strong><?php echo "$".$row['HarshitTotal'];?></strong></td>
						<td class="item-price"><strong><?php echo "$".$row['HarishTotal'];?></strong></td>
						<td class="item-price"><strong><?php echo "$".$row['DeepTotal'];?></strong></td>
						<td class="item-price"><strong><?php echo "$".$row['NishadTotal'];?></strong></td>
						<td colspan="2" class="item-price"><strong><?php echo "$".$row['Total'];?></strong></td>
					</tr>

				<?php endwhile; ?>
				</tbody>
			</table>

			<div class="btn_center">
			<form action ="index.php" method="post">
					<input type="submit" name="submitBill" id="submitBillButton" value="Submit Bill" class="button">
					<input type="submit" name="deleteBill" id="deleteBillButton" value="Clear Bill" class="button">
					<input type="button" name="PrintBill" id="PrintBillButton" value="Print Bill" class="button" onclick="createPDF()">
			</form>
			</div>


<?php } ?>

<?php
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

	$commGetTotalCostQuery = "SELECT ItemCost FROM items where ItemName = '$ItemName'";
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
	$SumTotalCost = $TotalCost*$ItemQuantity;
	$CommQuery = "INSERT INTO bills (`BillsID`, `BillItem`, `HarshitCost`, `HarishCost`, `DeepCost`, `NishadCost`, `TotalQty`, `TotalCost`) VALUES ('$cleanDate', '$ItemName', '$commIndCost', '$commIndCost', '$commIndCost', '$commIndCost', '$ItemQuantity', '$SumTotalCost');";
	$execCommQuery = mysqli_query($conn,$CommQuery);
	if (!$execCommQuery) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}
?>

<table class="center" style="table-layout:fixed;width:1000px;" id="Costs">
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
			$retrieveBill = "SELECT BillsID, BillItem, HarshitCost, HarishCost, DeepCost, NishadCost,TotalQty,TotalCost FROM Bills";
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

		<table class="center" style="table-layout:fixed;width:1000px;" id="Totals">
			<tbody>
				<?php
				$retrieveTotals = "SELECT SUM(HarshitCost) as HarshitTotal, SUM(HarishCost) as HarishTotal, SUM(DeepCost) as DeepTotal, SUM(NishadCost) as NishadTotal, SUM(TotalCost) as Total FROM Bills";
				$totalsresult = mysqli_query($conn,$retrieveTotals);
				$num_rows = mysqli_num_rows($totalsresult);

					while ($row = mysqli_fetch_array($totalsresult)):; ?>
				<tr>
					<td colspan="3"><strong class="item-name"><?php echo "Total";?></strong></td>
					<td class="item-price"><strong><?php echo "$".$row['HarshitTotal'];?></strong></td>
					<td class="item-price"><strong><?php echo "$".$row['HarishTotal'];?></strong></td>
					<td class="item-price"><strong><?php echo "$".$row['DeepTotal'];?></strong></td>
					<td class="item-price"><strong><?php echo "$".$row['NishadTotal'];?></strong></td>
					<td colspan="2" class="item-price"><strong><?php echo "$".$row['Total'];?></strong></td>
				</tr>

			<?php endwhile; ?>
			</tbody>
		</table>

		<div class="btn_center">
		<form action ="index.php" method="post">
				<input type="submit" name="submitBill" id="submitBillButton" value="Submit Bill" class="button">
				<input type="submit" name="deleteBill" id="deleteBillButton" value="Clear Bill" class="button">
				<input type="button" name="PrintBill" id="PrintBillButton" value="Print Bill" class="button" onclick="createPDF()">
		</form>
		</div>

<?php }
?>





	</body>
</html>
