<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Allow Users to Add or Edit Slips
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalUser";
$PageName = "Slips";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* If we are Editing or Updating */
if(isset($_POST['Action'])){
	//Make dates
		$StartDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['StartDate'])));
		$EndDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['EndDate'])));
	/* Edit */
	if($_POST['Action'] == "Edit"){
		if($_POST['DeleteSlip'] == "Yes"){
			$Query = "DELETE FROM `Slip` WHERE `SlipID` = " . EscapeSQLEntry($_POST['ID']);
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Slip ID # <a href='Slip.php?ID=" . EscapeSQLEntry($_POST['ID']) . "' style='font-decoration:none;color:orange;'>" . EscapeSQLEntry($_POST['ID']) . "</a> Removed.";
		} else {
			$Query = "UPDATE `Slip` SET `Consultant` = '" . EscapeSQLEntry($_POST['Consultant']) . "', `ClientID` = '" . EscapeSQLEntry($_POST['Client']) . "', `StartDate` = '" . $StartDate . "', `EndDate` = '" . $EndDate . "', `Hours` = '" . EscapeSQLEntry($_POST['Hours']) . "', `DNB` = '" . EscapeSQLEntry($_POST['DNB']) . "', `Description` = '" . EscapeSQLEntry($_POST['Description']) . "', `InternalNotes` = '" . EscapeSQLEntry($_POST['InternalNotes']) . "', `Price` = '" . EscapeSQLEntry($_POST['Price']) . "', `Quantity` = '" . EscapeSQLEntry($_POST['Quantity']) . "' WHERE `SlipID` = '" . EscapeSQLEntry($_POST['ID']) . "'";
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
				// Note to Self for V2.0 to add Redirect to Referrer
			$Message = "Slip ID # <a href='Slip.php?ID=" . $_POST['ID'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ID'] . "</a> Edited Successfully!";
		}
	/* Add New */
	} else if($_POST['Action'] == "AddNew"){
		$Query = "INSERT INTO `Slip` (TSType, Consultant, ClientID, StartDate, EndDate, Hours, DNB, Description, InternalNotes, Category, Price, Quantity) VALUES ('" . EscapeSQLEntry($_POST['TSType']) . "', '" . EscapeSQLEntry($_POST['Consultant']) . "', '" . EscapeSQLEntry($_POST['Client']) . "', '" . $StartDate . "', '" . $EndDate . "', '" . EscapeSQLEntry($_POST['Hours']) . "', '" . EscapeSQLEntry($_POST['DNB']) . "', '" . EscapeSQLEntry($_POST['Description']) . "', '" . EscapeSQLEntry($_POST['InternalNotes']) . "', '" . EscapeSQLEntry($_POST['Category']) . "', '" . EscapeSQLEntry($_POST['Price']) . "', '" . EscapeSQLEntry($_POST['Quantity']) . "')";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();

		$Message = "Slip ID # <a href='Slip.php?ID=" . $DatabaseConnection->lastInsertId() . "' style='font-decoration:none;color:orange;'>" . $DatabaseConnection->lastInsertId() . "</a> Inserted Successfully!";
	}
}

/* If editing, check the ID exists */
if(isset($_GET['ID']) && $_GET['ID'] != ""){
	$Query = "SELECT * FROM `Slip` WHERE `SlipID` = " . EscapeSQLEntry($_GET['ID']);
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Slip = $stm->fetchAll();
	$Slip = $Slip[0];
	$RowCount = $stm->rowCount();
	if($RowCount == 0){
		header('Location: index.php?Error=Error - Invalid ID');
		die();
		exit();
	}
}
// If it's a new Slip, set default dates to today
if(empty($Slip["StartDate"])){
	$Slip["StartDate"] = date("Y-m-d");
	$Slip["EndDate"] = date("Y-m-d");
}
// If no ID is set, show TS as Default
if(empty($Slip["TSType"])){
	$Slip["TSType"] = "TS";
}

/* Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<div id="main" style="width: 95%;">
	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Time Slips & Expenses</h2>
		</header>
		<div class="content">
			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>

				<form action="Slip.php" method="POST">

						<?php 
							/* If Slip is Invoiced already, If Invoice is Sent, disallow editing */
							if($Slip['InvoiceID'] != NULL){
								/* Check if Invoice is Sent */
								$CheckSent = "SELECT InvoiceID,InvoiceStatus,InvoiceHash FROM `Invoice` WHERE `InvoiceID` = " . $Slip['InvoiceID'];
								$stm = $DatabaseConnection->prepare($CheckSent);
								$stm->execute();
								$Invoice = $stm->fetchAll();
								if($Invoice[0]["InvoiceStatus"] == "1"){
									$DisableForm = true;
									echo "<h3 style='color:red;text-align:center;'>Error: This Slip is part of a Locked Invoice, you cannot edit this Slip! <a href='../Invoices/InvoiceDetails.php?ID=" . $Invoice[0]["InvoiceID"] . "'>Mark the Invoice as UnLocked to edit this Slip!</a>";
								} else {
									echo "<h3 style='color:red;text-align:center;'>Warning! This slip is part of an invoice, editing will edit the invoice as well!</h3>
										<p style='text-align:center;'>(If that was your intention, then that's fine)</p>";
								}

							}
							/* Adding or Editing */
							if(isset($_GET['ID']) && $_GET['ID'] != ""){
								echo "<h2>Slip ID: " . $_GET['ID'] . "</h2>";
								echo '<input type="hidden" name="Action" value="Edit">';
								echo '<input type="hidden" name="ID" value=' . EscapeSQLEntry($_GET['ID']) . '>';						
							} else {
								echo '<input type="hidden" name="Action" value="AddNew">';
							}
						?>
						<fieldset <?php if($DisableForm) echo 'disabled="disabled"'; ?>>    
							<!-- Standard Fields -->
							<div class="row">
								<div class="column">
									Consultant: <select name="Consultant" required="required">
												<?php 
													$UsersQuery = "SELECT 
																	`Name`,
													  				`ConsultantSlug`
											  					FROM `User`";
													$stm = $DatabaseConnection->prepare($UsersQuery);
													$stm->execute();
													$Users = $stm->fetchAll();
													foreach ($Users as $User) {
														echo "<option value=" . $User['ConsultantSlug'] . "";
															if($Slip['Consultant'] == $User['ConsultantSlug']){
																echo " selected='selected'";
															}
														echo ">" . $User['Name'] . "</option>";
													}
												?>
											</select>
									</div>
									<div class="column">
										Client: 	<select name="Client" required="required">
													<?php 
														$ClientsQuery = "SELECT 
																		`ClientName`,
														  				`ClientID`
												  					FROM `Client`";
														$stm = $DatabaseConnection->prepare($ClientsQuery);
														$stm->execute();
														$Clients = $stm->fetchAll();
														foreach ($Clients as $Client) {
															echo "<option value=" . $Client['ClientID'] . "";
																if($Slip['ClientID'] == $Client['ClientID']){
																	echo " selected='selected'";
																}
															echo ">" . $Client['ClientName'] . "</option>";
														}
													?>
												</select>
									</div>
								</div>
									<br>
								<div class="row">
									<div class="column">
										Start Date:
											<input type="date" name="StartDate" value="<?php echo $Slip["StartDate"]; ?>" required="required">
									</div>
									<div class="column">
										End Date:
											<input type="date" name="EndDate" value="<?php echo $Slip["EndDate"]; ?>">
									</div>
								</div>
									<hr>
									Slip Type: 
										<select name="TSType" required="required" id="TSType">
											<option value="TS" <?php if($Slip["TSType"] == "TS") echo " selected='selected'"; ?>>TS</option>
											<option value="Expense" <?php if($Slip["TSType"] == "Expense") echo " selected='selected'"; ?>>Expense</option>
										</select>

									<hr>
								<section id="TS" <?php if($Slip["TSType"] != "TS") echo "style='display:none;'"; ?>>
									<div class="row">
										<div class="column">
											Hours: <input type="number" name="Hours" min="0" step="any" value="<?php echo $Slip["Hours"]; ?>" style="color:black;">
										</div>
										<div class="column">
											DNB: <input type="number" name="DNB" min="0" step="any" value="<?php echo $Slip["DNB"]; ?>" style="color:black;">
										</div>						
									</div>
								</section>
								<section id="Expense" <?php if($Slip["TSType"] != "Expense") echo "style='display:none;'"; ?>>
									<div class="row">
										<div class="column">
											Price: <input type="number" name="Price" min="0" step="any" value="<?php echo $Slip["Price"]; ?>" style="color:black;">
										</div>
										<div class="column">
											Quantity: <input type="number" name="Quantity" min="0" step="any" value="<?php echo $Slip["Quantity"]; ?>" style="color:black;">
										</div>						
									</div>
								</section>
									<hr>
								Description:
									<textarea name="Description" required="required"><?php echo $Slip['Description']; ?></textarea><br>

								Internal Notes:
									<input type="text" name="InternalNotes" value="<?php echo $Slip["InternalNotes"]; ?>"><br>

								Category:
									<select name="Category">
										<?php
											foreach ($slip_categories as $Cat) {
												echo "<option value='" . $Cat . "'";
													if($Slip['Category'] == $Cat){ echo " selected='selected'";}
												echo ">" . $Cat . "</option>";
											}
										?>
									</select><br>
								<?php
									if(isset($_GET['ID']) && $_GET['ID'] != ""){
										echo "<input type='checkbox' name='DeleteSlip' value='Yes' id='DeleteSlip'><label for='DeleteSlip'>Delete Slip?</label><br>";
									}
								?>
						<input type="submit" name="" value="Save">
					</fieldset>
				</form>			
		</div>
	</section>
</div>
<script type="text/javascript">
	$('#TSType').on('change', function() {
	  if(this.value == "TS"){
	  	$("#TS").show();
	  	$("#Expense").hide();
	  } else {
	  	$("#TS").hide();
	  	$("#Expense").show();
	  }
	});
</script>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>