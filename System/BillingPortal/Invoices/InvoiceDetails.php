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
* Allow Users to Add or Edit Invoices
*/

/* Include System Functions */
require_once("../Functions.php");

/* Init System */
Init(false);

/* Set System */
$CurrentSystem = "BillingPortal";

$PageName = "Invoice";

/* If we are Editing or Updating */
if(isset($_REQUEST['Action'])){

	/* Lock or UnLock Invoice */
	if($_REQUEST['Action'] == "ChangeLock"){
		$Query = "UPDATE `Invoice` SET `InvoiceStatus` = " . EscapeSQLEntry($_REQUEST['Status']) . " WHERE `InvoiceID` = " . EscapeSQLEntry($_REQUEST['ID']);
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
	}

	/* Update Invoice */
	if($_REQUEST['Action'] == "Edit"){

		/* Check if Invoice is Unlocked or Locked */
		$InvoiceStatus = "SELECT `InvoiceStatus` FROM `Invoice` WHERE `InvoiceID` = " . EscapeSQLEntry($_POST['ID']);
		$stm = $DatabaseConnection->prepare($InvoiceStatus);
		$stm->execute();
		$InvoiceStatus = $stm->fetchAll();

		/* Unlocked */
			if($InvoiceStatus[0]["InvoiceStatus"] == "0"){
				//Make dates
					$InvoiceDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['InvoiceDate'])));
					$PaymentDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['PaymentDate'])));

				// Set Columns to Show
				
					$ColumnsToShow->Consultant = "0";
					$ColumnsToShow->Description = "0";
					$ColumnsToShow->Hours_Price = "0";
					$ColumnsToShow->Rate_Quantity = "0";
					$ColumnsToShow->Amount = "0";
					
					if(in_array("Consultant", $_POST['Cols'])){
						$ColumnsToShow->Consultant = "1";
					}
					if(in_array("Description", $_POST['Cols'])){
						$ColumnsToShow->Description = "1";
					}
					if(in_array("Hours_Price", $_POST['Cols'])){
						$ColumnsToShow->Hours_Price = "1";
					}
					if(in_array("Rate_Quantity", $_POST['Cols'])){
						$ColumnsToShow->Rate_Quantity = "1";
					}
					if(in_array("Amount", $_POST['Cols'])){
						$ColumnsToShow->Amount = "1";
					}

					$ColumnsToShow = json_encode($ColumnsToShow);

				$UpdateInvoice = "UPDATE `Invoice` SET `ClientID` = '" . EscapeSQLEntry($_POST['Client']) . "', `InvoiceType` = '" . EscapeSQLEntry($_POST['InvoiceType']) . "', `FlatRateMonths` = '" . EscapeSQLEntry($_POST['FlatRateMonths']) . "', `InvoiceDate` = '" . $InvoiceDate . "', `ColumnsToShow` = '" . $ColumnsToShow . "', `DiscountType` = '" . EscapeSQLEntry($_POST['DiscountType']) . "',`DiscountAmount` = '" . EscapeSQLEntry($_POST['DiscountAmount']) . "',`InvoiceNotes` = '" . EscapeSQLEntry($_POST['InvoiceNotes']) . "',`PaymentStatus` = '" . EscapeSQLEntry($_POST['PaymentStatus']) . "',`PaymentAmount` = '" . EscapeSQLEntry($_POST['PaymentAmount']) . "',`PaymentDate` = '" . $PaymentDate . "',`PaymentNotes` = '" . EscapeSQLEntry($_POST['PaymentNotes']) . "',`InternalNotes` = '" . EscapeSQLEntry($_POST['InternalNotes']) . "' WHERE `InvoiceID` = " . EscapeSQLEntry($_POST['ID']). "";
		/* Locked */	
			} else if($InvoiceStatus[0]["InvoiceStatus"] == "1"){
				// Make Date
					$PaymentDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['PaymentDate'])));

				$UpdateInvoice = "UPDATE `Invoice` SET `PaymentStatus` = '" . EscapeSQLEntry($_POST['PaymentStatus']) . "',`PaymentAmount` = '" . EscapeSQLEntry($_POST['PaymentAmount']) . "',`PaymentDate` = '" . $PaymentDate . "',`PaymentNotes` = '" . EscapeSQLEntry($_POST['PaymentNotes']) . "',`InternalNotes` = '" . EscapeSQLEntry($_POST['InternalNotes']) . "' WHERE `InvoiceID` = " . EscapeSQLEntry($_POST['ID']). "";
			}

				$stm = $DatabaseConnection->prepare($UpdateInvoice);
				$stm->execute();
				$Message = "Invoice ID # <a href='Invoice.php?ID=" . $_POST['ID'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ID'] . "</a> Updated Successfully!";

	}

	/* Add Invoice */	
	if($_REQUEST['Action'] == "Add"){
		//Make dates
			$InvoiceDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['InvoiceDate'])));
			$PaymentDate = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['PaymentDate'])));

		// Set Columns to Show
		
			$ColumnsToShow->Consultant = "0";
			$ColumnsToShow->Description = "0";
			$ColumnsToShow->Hours_Price = "0";
			$ColumnsToShow->Rate_Quantity = "0";
			$ColumnsToShow->Amount = "0";
			
			if(in_array("Consultant", $_POST['Cols'])){
				$ColumnsToShow->Consultant = "1";
			}
			if(in_array("Description", $_POST['Cols'])){
				$ColumnsToShow->Description = "1";
			}
			if(in_array("Hours_Price", $_POST['Cols'])){
				$ColumnsToShow->Hours_Price = "1";
			}
			if(in_array("Rate_Quantity", $_POST['Cols'])){
				$ColumnsToShow->Rate_Quantity = "1";
			}
			if(in_array("Amount", $_POST['Cols'])){
				$ColumnsToShow->Amount = "1";
			}

			$ColumnsToShow = json_encode($ColumnsToShow);

			//Invoice Hash
			$InvoiceHash = bin2hex(random_bytes(32));

		$InsertInvoice = "INSERT INTO `Invoice` (InvoiceHash, ClientID, InvoiceType, FlatRateMonths, InvoiceDate, ColumnsToShow, DiscountType, DiscountAmount, InvoiceNotes, PaymentStatus, PaymentAmount, PaymentDate, PaymentNotes, InternalNotes) VALUES ('" . $InvoiceHash . "', '" . EscapeSQLEntry($_POST['Client']) . "', '" . EscapeSQLEntry($_POST['InvoiceType']) . "', '" . EscapeSQLEntry($_POST['FlatRateMonths']) . "', '" . $InvoiceDate . "', '" . $ColumnsToShow . "', '" . EscapeSQLEntry($_POST['DiscountType']) . "', '" . EscapeSQLEntry($_POST['DiscountAmount']) . "', '" . EscapeSQLEntry($_POST['InvoiceNotes']) . "', '" . EscapeSQLEntry($_POST['PaymentStatus']) . "', '" . EscapeSQLEntry($_POST['PaymentAmount']) . "', '" . $PaymentDate . "', '" . EscapeSQLEntry($_POST['PaymentNotes']) . "', '" . EscapeSQLEntry($_POST['InternalNotes']) . "')";
		$stm = $DatabaseConnection->prepare($InsertInvoice);
		$stm->execute();
		header('Location: BuildInvoice.php?ID=' . $DatabaseConnection->lastInsertId());
	}
}


/* If editing, check the ID exists */
if(isset($_GET['ID']) && $_GET['ID'] != ""){
	$Query = "SELECT * FROM `Invoice` WHERE `InvoiceID` = " . EscapeSQLEntry($_GET['ID']);
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Invoice = $stm->fetchAll();
	$Invoice = $Invoice[0];
	$RowCount = $stm->rowCount();

	if($RowCount == 0){
		header('Location: index.php?Error=Error - Invalid ID');
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
			<h2>Invoice</h2>
		</header>
		<div class="content">
			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>
				<?php

					if(isset($Invoice['InvoiceStatus']) && $Invoice['InvoiceStatus'] == "1"){
						echo "<h2>Invoice ID: " . $_GET['ID'] . " - <span>Invoice Status: <strong style='color:red;'>Locked</strong></span></h2>";
						echo "<form action='InvoiceDetails.php?ID=" . $_GET['ID'] . "' method='POST'>
								<input type='hidden' name='Action' value='ChangeLock'>
								<input type='hidden' name='Status' value='0'>
								<input type='submit' value='Unlock Invoice' class='button large'>
							</form><br>";
							echo "<a class='button large' href='Invoice.php?ID=" . $Invoice['InvoiceHash'] . "'>View Invoice</a>";
						$DisableForm = true;
					} else if(isset($Invoice['InvoiceStatus'])){
						echo "<h2>Invoice ID: " . $_GET['ID'] . " - <span>Invoice Status: <strong style='color:green;'>Unlocked</strong></span></h2>";
						echo "<form action='InvoiceDetails.php?ID=" . $_GET['ID'] . "' method='POST'>
								<input type='hidden' name='Action' value='ChangeLock'>
								<input type='hidden' name='Status' value='1'>
								<input type='submit' value='Lock Invoice' class='button large'>
							</form><br>";
						echo "<div class='row'>";
							echo "<div class='column'><a class='button large' href='Build/?ID=" . $_GET['ID'] . "'>Re-Build Invoice</a></div>";
							echo "<div class='column'><a class='button large' href='Invoice.php?ID=" . $Invoice['InvoiceHash'] . "'>View Invoice</a></div>";
						echo "</div>";
						$DisableForm = false;
					} else {
						echo "<h2>Create Invoice</h2>";
					}
				?>

				<form action="InvoiceDetails.php?ID=<?php echo $_GET['ID']; ?>" method="POST">
						<?php
							if(isset($Invoice['InvoiceStatus']) && $Invoice['InvoiceStatus'] == "1"){
								echo '<input type="hidden" name="Action" value="Edit">';								
							} else if(isset($Invoice['InvoiceStatus'])){
								echo '<input type="hidden" name="Action" value="Edit">';	
							} else {
								echo '<input type="hidden" name="Action" value="Add">';	
							}
							echo '<input type="hidden" name="ID" value=' . EscapeSQLEntry($_GET['ID']) . '>';	
						?>
						
							<hr>
						<!-- Invoice Fields -->
							<fieldset<?php if($DisableForm) echo ' disabled="disabled"'; ?>>    
								
								<h3>General Information</h3>
								<div class="row">
									<div class="column">
										Client: 	<select name="Client" required="required">
														<?php 
															$ClientsQuery = "SELECT
																			`ClientID`, 
																			`ClientName`,
															  				`Slug`
													  					FROM `Clients`";
															$stm = $DatabaseConnection->prepare($ClientsQuery);
															$stm->execute();
															$Clients = $stm->fetchAll();
															foreach ($Clients as $Client) {
																echo "<option value=" . $Client['ClientID'] . "";
																	if($Invoice['ClientID'] == $Client['ClientID']){
																		echo " selected='selected'";
																	}
																echo ">" . $Client['ClientName'] . "</option>";
															}
														?>
													</select>
										</div>
										<div class="column"></div>
									</div>
										<br>
									<div class="row">
										<div class="column">
											Invoice Type:
												<select name="InvoiceType" required="required">
													<option value="Flat" <?php if($Invoice['InvoiceType'] == "Flat") echo "selected='selected'"; ?>>Flat</option>
													<option value="Hourly" <?php if($Invoice['InvoiceType'] == "Hourly") echo "selected='selected'"; ?>>Hourly</option>
												</select>
										</div>
										<div class="column">
											Invoice Months (For Flat Rate ONLY):<br>
												<input type="number" name="FlatRateMonths" value="<?php echo $Invoice["FlatRateMonths"]; ?>" style="color:black;" min="1">												
										</div>
										<div class="column">
											Date:<br>
												<input type="date" name="InvoiceDate" value="<?php echo $Invoice["InvoiceDate"]; ?>" required="required">
										</div>
									</div>
										<hr>
										<h3>Invoice Details</h3>
									<div class="row">
										<div class="column">
											Invoice Columns to Show: <br>
												<?php
													$Cols = json_decode($Invoice['ColumnsToShow'], true);
												?>
												<input type='checkbox' name='Cols[]' value='Consultant' id="Consultant" <?php if($Cols["Consultant"] == "1") {echo "checked";}; ?>> <label for="Consultant">Consultant</label><br>
												<input type='checkbox' name='Cols[]' value='Description' id="Description" <?php if($Cols["Description"] == "1") {echo "checked";}; ?>> <label for="Description">Description</label><br>
												<input type='checkbox' name='Cols[]' value='Hours_Price' id="Hours_Price" <?php if($Cols["Hours_Price"] == "1") {echo "checked";}; ?>> <label for="Hours_Price">Hours/Price</label><br>
												<input type='checkbox' name='Cols[]' value='Rate_Quantity' id="Rate_Quantity" <?php if($Cols["Rate_Quantity"] == "1") {echo "checked";}; ?>> <label for="Rate_Quantity">Rate/Quantity</label><br>
												<input type='checkbox' name='Cols[]' value='Amount' id="Amount" <?php if($Cols["Amount"] == "1") {echo "checked";}; ?>> <label for="Amount">Amount</label><br>
										</div>
										<div class="column">
											Discount Type:
												<select name="DiscountType">
													<option value="1" <?php if($Invoice['DiscountType'] == "1") echo "selected='selected'"; ?>>Flat</option>
													<option value="2" <?php if($Invoice['DiscountType'] == "2") echo "selected='selected'"; ?>>Percent</option>
												</select>
										</div>
										<div class="column">
											Discount Amount (For none, enter 0): <br>
												<input type="number" name="DiscountAmount" value="<?php echo $Invoice["DiscountAmount"]; ?>" min="0" step="any" style="color:black;" required="required">
										</div>
									</div>
									<div class="row">
										<div class="column">
											Invoice Notes:
												<textarea name="InvoiceNotes"><?php echo $Invoice["InvoiceNotes"]; ?></textarea>
										</div>
									</div>
								</fieldset>
										<hr>
									<h3>Payment Options</h3>
									<div class="row">
										<div class="column">
											Payment Status:
												<select name="PaymentStatus" required="required">
													<option value="0" <?php if($Invoice['PaymentStatus'] == "0") echo "selected='selected'"; ?>>Not Paid</option>
													<option value="1" <?php if($Invoice['PaymentStatus'] == "1") echo "selected='selected'"; ?>>Partial Payment</option>
													<option value="2" <?php if($Invoice['PaymentStatus'] == "2") echo "selected='selected'"; ?>>Fully Paid</option>
												</select>
										</div>
										<div class="column">
											Payment Amount: <br>
												<input type="number" name="PaymentAmount" value="<?php if(isset($Invoice["PaymentAmount"])) {echo $Invoice["PaymentAmount"]; } else echo '0'; ?>" min="0" step="any" style="color:black;">
										</div>
										<div class="column">
											Payment Date: <br>
												<input type="date" name="PaymentDate" value="<?php echo $Invoice["PaymentDate"]; ?>" style="color:black;">
										</div>
									</div>
										<br>
									<div class="row">
										<div class="column">
											Payment Notes:
												<textarea name="PaymentNotes"><?php echo $Invoice["PaymentNotes"]; ?></textarea>
										</div>
										<div class="column">
											Invoice Notes (Internal):
												<textarea name="InternalNotes"><?php echo $Invoice["InternalNotes"]; ?></textarea>
										</div>
									</div>
										<br>
						<input type="submit" name="Submit" value="Save">
				</form>			
		</div>
	</section>
</div>
<?php require_once('../../../SystemAssets/Views/BillingPortalFooter.php');  ?>