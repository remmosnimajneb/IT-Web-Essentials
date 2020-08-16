<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/*
* Add or Edit Clients
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Clients";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* If we are Editing or Updating */
if(isset($_POST['Action'])){
	/* Edit */
	if($_POST['Action'] == "Edit"){
		if($_POST['DeleteClient'] == "Yes"){
				$Query = "DELETE FROM `Client` WHERE `ClientID` = '" . EscapeSQLEntry($_POST['Client']) . "'";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Client " . $_POST['ClientName'] . "</a> Deleted";
			} else {
				$sql = "UPDATE `Clients` SET `ClientName` = '" . EscapeSQLEntry($_POST['ClientName']) . "', `StreetName` = '" . EscapeSQLEntry($_POST['StreetName']) . "', `City` = '" . EscapeSQLEntry($_POST['City']) . "', `State` = '" . EscapeSQLEntry($_POST['State']) . "', `ZIP` = '" . EscapeSQLEntry($_POST['ZIP']) . "', `Email` = '" . EscapeSQLEntry($_POST['Email']) . "', `Phone` = '" . EscapeSQLEntry($_POST['Phone']) . "', `FlatRate` = '" . EscapeSQLEntry($_POST['FlatRate']) . "', `HourlyDefaultRate` = '" . EscapeSQLEntry($_POST['HourlyDefaultRate']) . "', `Notes` = '" . EscapeSQLEntry($_POST['Notes']) . "' WHERE `ClientID` = '" . EscapeSQLEntry($_POST['Client']) . "'";
				$stm = $DatabaseConnection->prepare($sql);
				$stm->execute();
				$Message = "Client <a href='Client.php?Client=" . $_POST['Client'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ClientName'] . "</a> Edited Successfully!";
			}	
	/* Add New */
	} else if($_POST['Action'] == "AddNew"){
		/* Check Slug not already used */
		$Query = "SELECT * FROM `Client` WHERE `ClientSlug` = '" . EscapeSQLEntry($_POST['ClientSlug']) . "'";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		if($stm->rowCount() == 0){
			$sql = "INSERT INTO `Client` (ClientName, ClientSlug, StreetName, City, State, ZIP, Email, Phone, FlatRate, HourlyDefaultRate, Notes) VALUES ('" . EscapeSQLEntry($_POST['ClientName']) . "', '" . EscapeSQLEntry($_POST['ClientSlug']) . "', '" . EscapeSQLEntry($_POST['StreetName']) . "', '" . EscapeSQLEntry($_POST['City']) . "', '" . EscapeSQLEntry($_POST['State']) . "', '" . EscapeSQLEntry($_POST['ZIP']) . "', '" . EscapeSQLEntry($_POST['Email']) . "', '" . EscapeSQLEntry($_POST['Phone']) . "', '" . EscapeSQLEntry($_POST['FlatRate']) . "', '" . EscapeSQLEntry($_POST['HourlyDefaultRate']) . "', '" . EscapeSQLEntry($_POST['Notes']) . "')";
			$stm = $DatabaseConnection->prepare($sql);
			$stm->execute();
			$Message = "Client <a href='Client.php?Client=" . $DatabaseConnection->lastInsertId() . "' style='font-decoration:none;color:orange;'>" . $_POST['ClientName'] . "</a> Inserted Successfully!";
		} else {
			$Message = "Error! Slug already taken by another client! Please choose another Client Slug";
			// Pass the values back from the POST so user doesn't need to re-enter values again :)
			$Client = $_POST;
		}

	}
}

/* If editing, check the Client exists */
if(isset($_GET['Client']) && $_GET['Client'] != ""){
	$Query = "SELECT * FROM `Client` WHERE `ClientID` = '" . EscapeSQLEntry($_GET['Client']) . "'";
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Client = $stm->fetchAll()[0];
	if($stm->rowCount() == 0){
		header('Location: index.php?Error=Error - Invalid Client');
		die();
		exit();
	}
}

/* Get Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
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
								$NewClient = true;
								echo '<input type="hidden" name="Action" value="AddNew">';
							}
						?>
						<div class="row">
							<div class="column">
								Client Name:
									<input type="text" name="ClientName" value="<?php echo $Client["ClientName"]; ?>" required="required">
							</div>
							<div class="column">
								Slug: <?php if(!$NewClient) echo "[Slug cannot be changed!]"; else echo "[Note: Slug cannot be changed later!]"; ?>
									<input type="text" name="ClientSlug" value="<?php echo $Client["ClientSlug"]; ?>" <?php if(!$NewClient) echo 'readonly="readonly"'; ?>>
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
						<div class="row">
							<div class="column">
								Notes <textarea><?php echo $Client['Notes']; ?></textarea>
							</div>
						</div>
							<br>
						<?php if(!$NewClient){ ?>
							<input type='checkbox' name='DeleteClient' value='Yes' id='Delete'><label for='Delete'>Delete Client? </label>
							<br><br>
						<?php } ?>
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
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>