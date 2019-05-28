<?php

$servername = "";
$username = "";
$pwd = "";
$dbname = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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

<h1>View Bills</h1>
<a href="index.php" class="button" >Back</a><br><br>

<form action="ViewBills.php" method="post" align="center">
  <label for="billid">Enter Bill ID: </label>
  <input type="text" id="billIdText" name="billIdText" class="inputs">

  <br>
  <input type="submit" name="searchBill" value="Search" class="button">
	<input type="submit" name="EmailBill" value="Email Bill" class="button">
  <br><br>
</form>

<?php
$billid = '';

if (isset($_POST['EmailBill'])){
$billid = $_POST['billIdText'];
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
//require '../phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer(true);
$mail->Host = "smtp.gmail.com";
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Username = "abc@gmail.com";
$mail->Password = "xxxxxxxx!";

$mail->SMTPSecure = "tls";

$mail->Port = 587;

			$retrieveTotals = "SELECT BillsID,SUM(HarshitCost) as HarshitTotal, SUM(HarishCost) as HarishTotal, SUM(DeepCost) as DeepTotal, SUM(NishadCost) as NishadTotal, SUM(TotalCost) as Total FROM billslist where BillsID = $billid";
			$totalsresult = mysqli_query($conn,$retrieveTotals);
			$num_rows = mysqli_num_rows($totalsresult);
			$BillIDforEmail = '';
			$HarshitTotalForEmail = '';
			$HarishTotalForEmail = '';
			$NishadTotalForEmail = '';

			while ($row = mysqli_fetch_array($totalsresult)):;

			$BillIDforEmail = $row['BillsID'];
			$HarshitTotalForEmail = $row['HarshitTotal'];
			$HarishTotalForEmail = $row['HarishTotal'];
			$DeepTotalForEmail = $row['DeepTotal'];
			$NishadTotalForEmail = $row['NishadTotal'];

			 endwhile;

$mail->Subject = "Bill Details For Bill ID: ".$BillIDforEmail;

$mail->Body = "VALERO BILL: 05/10/2019\nHarshit: $".$HarshitTotalForEmail."\nHarish: $".$HarishTotalForEmail."\nDeep: $".$DeepTotalForEmail."\nNishad: $".$NishadTotalForEmail."\n+Tax $1.04 per head.";

$mail->setFrom('abc@gmail.com','Nishad');
$mail->addAddress('abc@gmail.com');
$mail->addAddress('def@gmail.com');
$mail->addAddress('ghi@gmail.com');
$mail->addAddress('jkl@gmail.com');

if ($mail->send()) {
	echo '<script language="javascript">';
	echo 'alert("Email sent successfully.")';
	echo '</script>';
}
else {
	echo "Failed";
}

}

if (isset($_POST['searchBill'])){
$billid = $_POST['billIdText'];
?>
<table class="center" style="table-layout:fixed;width:1000px;">
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
      $retrieveBill = "SELECT BillsID, BillItem, HarshitCost, HarishCost, DeepCost, NishadCost,TotalQty,TotalCost FROM billslist where BillsID = $billid";
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

    <table class="center" style="table-layout:fixed;width:1000px;">
      <tbody>
        <?php
        $retrieveTotals = "SELECT SUM(HarshitCost) as HarshitTotal, SUM(HarishCost) as HarishTotal, SUM(DeepCost) as DeepTotal, SUM(NishadCost) as NishadTotal, SUM(TotalCost) as Total FROM billslist where BillsID = $billid";
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
  <?php } ?>
</body>
