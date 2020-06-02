<?php
/********************************
* Project: IT Web Essentials 
* Code Version: 1.0
* Author: Benjamin Sommer
* Company: The Berman Consulting Group - https://bermangroup.com
* GitHub: https://github.com/remmosnimajneb
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Add or Edit Clients
*/

/* Include System Functions */
require_once("../Functions.php");

/* Init System */
Init(false);

/* Set System */
$CurrentSystem = "BillingPortal";

$PageName = "Client";

/* If we are Editing or Updating */
if(isset($_POST['Action'])){

	/* Edit */
	if($_POST['Action'] == "Edit"){
		if($_POST['DeleteClient'] == "Yes"){
				$Query = "DELETE FROM `Clients` WHERE `Slug` = '" . EscapeSQLEntry($_POST['Slug']) . "'";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				// Note to Self for V2.0 to add Redirect to Referrer
				$Message = "Client " . $_POST['ClientName'] . "</a> Deleted";
			} else {
				$sql = "UPDATE `Clients` SET `ClientName` = '" . EscapeSQLEntry($_POST['ClientName']) . "', `StreetName` = '" . EscapeSQLEntry($_POST['StreetName']) . "', `City` = '" . EscapeSQLEntry($_POST['City']) . "', `State` = '" . EscapeSQLEntry($_POST['State']) . "', `ZIP` = '" . EscapeSQLEntry($_POST['ZIP']) . "', `Email` = '" . EscapeSQLEntry($_POST['Email']) . "', `Phone` = '" . EscapeSQLEntry($_POST['Phone']) . "', `FlatRate` = '" . EscapeSQLEntry($_POST['FlatRate']) . "', `HourlyDefaultRate` = '" . EscapeSQLEntry($_POST['HourlyDefaultRate']) . "' WHERE `Slug` = '" . EscapeSQLEntry($_POST['Slug']) . "'";
				$stm = $DatabaseConnection->prepare($sql);
				$stm->execute();

				// Note to Self for V2.0 to add Redirect to Referrer
				$Message = "Client <a href='Client.php?Client=" . $_POST['Slug'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ClientName'] . "</a> Edited Successfully!";
			}	

	/* Add New */
	} else if($_POST['Action'] == "AddNew"){
		$sql = "INSERT INTO `Clients` (ClientName, Slug, StreetName, City, State, ZIP, Email, Phone, FlatRate, HourlyDefaultRate) VALUES ('" . EscapeSQLEntry($_POST['ClientName']) . "', '" . EscapeSQLEntry($_POST['Slug']) . "', '" . EscapeSQLEntry($_POST['StreetName']) . "', '" . EscapeSQLEntry($_POST['City']) . "', '" . EscapeSQLEntry($_POST['State']) . "', '" . EscapeSQLEntry($_POST['ZIP']) . "', '" . EscapeSQLEntry($_POST['Email']) . "', '" . EscapeSQLEntry($_POST['Phone']) . "', '" . EscapeSQLEntry($_POST['FlatRate']) . "', '" . EscapeSQLEntry($_POST['HourlyDefaultRate']) . "')";
		$stm = $DatabaseConnection->prepare($sql);
		$stm->execute();
		
		$Message = "Client <a href='Client.php?Client=" . $_POST['Slug'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ClientName'] . "</a> Inserted Successfully!";
	}
}


/* If editing, check the Client exists */
if(isset($_GET['Client']) && $_GET['Client'] != ""){
	$Query = "SELECT * FROM `Clients` WHERE `Slug` = '" . EscapeSQLEntry($_GET['Client']) . "'";
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Client = $stm->fetchAll();
	$Client = $Client[0];
	$RowCount = $stm->rowCount();

	if($RowCount == 0){
		header('Location: index.php?Error=Error - Invalid Client');
		die();
		exit();
	}
}

/* Include Header */
require_once('../../../SystemAssets/Views/BillingPortalHeader.php');
?>

<div id="main" style="width: 95%;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Client</h2>
		</header>
		<div class="content">

			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>
		
				<form action="Client.php" method="POST">
						
						<?php 
							/* Adding or Editing */
							if(isset($_GET['Client']) && $_GET['Client'] != ""){
								echo "<h2>Client Name: " . $Client['ClientName'] . "</h2>";
								echo '<input type="hidden" name="Action" value="Edit">';
								echo '<input type="hidden" name="Client" value=' . EscapeSQLEntry($_GET['Client']) . '>';						
							} else {
								echo '<input type="hidden" name="Action" value="AddNew">';
							}
						?>

						<div class="row">
							<div class="column">
								Client Name:
									<input type="text" name="ClientName" value="<?php echo $Client["ClientName"]; ?>" required="required">
							</div>
							<div class="column">
								Slug: <?php if(isset($Client["Slug"])) echo "[Slug cannot be changed!]"; else echo "[Note: Slug cannot be changed later!]"; ?>
									<input type="text" name="Slug" value="<?php echo $Client["Slug"]; ?>" <?php if(isset($Client["Slug"])) echo 'readonly="readonly"'; ?>>
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Street Name:
									<input type="text" name="StreetName" value="<?php echo $Client["StreetName"]; ?>">
							</div>
							<div class="column">
								City:
									<input type="text" name="City" value="<?php echo $Client["City"]; ?>">
							</div>
							<div class="column">
								State:
									<input type="text" name="State" value="<?php echo $Client["State"]; ?>">
							</div>
							<div class="column">
								ZIP:
									<input type="text" name="ZIP" value="<?php echo $Client["ZIP"]; ?>">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Email:
									<input type="email" name="Email" value="<?php echo $Client["Email"]; ?>">
							</div>
							<div class="column">
								Phone:
									<input type="text" name="Phone" value="<?php echo $Client["Phone"]; ?>">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Flat Rate:
									<input type="text" name="FlatRate" value="<?php echo $Client["FlatRate"]; ?>">
							</div>
							<div class="column">
								Defualt Hourly Rate:
									<input type="text" name="HourlyDefaultRate" value="<?php echo $Client["HourlyDefaultRate"]; ?>">
							</div>
						</div>
							<br>
						<input type='checkbox' name='DeleteClient' value='Yes' id='Delete'><label for='Delete'>Delete Client? </label>
							<br><br>
					<input type="submit" name="" value="Save">
				</form>			
		</div>
	</section>
</div>
<style type="text/css">

	@media only screen and (max-width: 768px) {
		input, select {
			width: 100%;
		}
		textarea {
			width: 100%;
		}
	}
</style>
<?php require_once('../../../SystemAssets/Views/BillingPortalFooter.php');  ?>