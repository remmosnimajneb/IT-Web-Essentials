<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.2
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Allow Users to Add or Edit Subscriptions
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Subscription Details";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* If we are Editing or Updating */
if(isset($_REQUEST['Action'])){

	
	/* Update Invoice */
	if($_REQUEST['Action'] == "Edit"){

		/* Delete Invoice */
		if($_POST['DeleteSubscription'] == "Yes"){

			$Query = "DELETE FROM `Subscription` WHERE `SubscriptionID` = " . EscapeSQLEntry($_POST['ID']);
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Subscription Deleted Successfully!";

		} else {

			//Make dates
				$_POST['StartDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['StartDate'])));
				$_POST['RecurrenceDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['RecurrenceDate'])));


			$Query = "UPDATE `Subscription` SET `SubscriptionName` = '" . EscapeSQLEntry($_POST['SubscriptionName']) . "', `SlipType` = '" . EscapeSQLEntry($_POST['SlipType']) . "', `ClientID` = '" . EscapeSQLEntry($_POST['Client']) . "', `Cost` = '" . EscapeSQLEntry($_POST['Cost']) . "', `Quantity` = '" . EscapeSQLEntry($_POST['Quantity']) . "', `StartDate` = '" . EscapeSQLEntry($_POST['StartDate']) . "', `Frequency` = '" . EscapeSQLEntry($_POST['Frequency']) . "', `RecurrenceOn` = '" . EscapeSQLEntry($_POST['RecurrenceOn']) . "', `RecurrenceDate` = '" . EscapeSQLEntry($_POST['RecurrenceDate']) . "', `Status` = '" . EscapeSQLEntry($_POST['Status']) . "' WHERE `SubscriptionID` = '" . EscapeSQLEntry($_POST['ID']) . "'";
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Subscription ID # <a href='SubscriptionDetails.php?ID=" . $_POST['ID'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ID'] . "</a> Updated Successfully!";

		} // End Else

	}

	/* Add Invoice */	
	if($_REQUEST['Action'] == "Add"){
		
		//Make dates
			$_POST['StartDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['StartDate'])));
			$_POST['RecurrenceDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['RecurrenceDate'])));

		$Query = "INSERT INTO `Subscription` (`SubscriptionID`, `SubscriptionName`, `ClientID`, `SlipType`, `Cost`, `Quantity`, `StartDate`, `Frequency`, `RecurrenceOn`, `RecurrenceDate`, `LastRunDate`, `Status`) VALUES (NULL, '" . EscapeSQLEntry($_POST['SubscriptionName']) . "', '" . EscapeSQLEntry($_POST['Client']) . "', '" . EscapeSQLEntry($_POST['SlipType']) . "', '" . EscapeSQLEntry($_POST['Cost']) . "', '" . EscapeSQLEntry($_POST['Quantity']) . "', '" . EscapeSQLEntry($_POST['StartDate']) . "', '" . EscapeSQLEntry($_POST['Frequency']) . "', '" . EscapeSQLEntry($_POST['RecurrenceOn']) . "', '" . EscapeSQLEntry($_POST['RecurrenceDate']) . "', NULL, '" . EscapeSQLEntry($_POST['Status']) . "'); ";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		header('Location: SubscriptionDetails.php?ID=' . $DatabaseConnection->lastInsertId());
	}
}


/* If editing, check the ID exists */
if(isset($_GET['ID']) && $_GET['ID'] != ""){
	$Query = "SELECT * FROM `Subscription` WHERE `SubscriptionID` = " . EscapeSQLEntry($_GET['ID']);
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Subscription = $stm->fetchAll()[0];

	// No row exists, throw an error
	if($stm->rowCount() == 0){
		header('Location: index.php?Error=Error - Invalid ID');
		die();
	}
}

	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');
?>

<div id="main" style="width: 95%;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Subscription</h2>
		</header>
		<div class="content">
			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>
				<?php

					if(isset($Subscription['Status'])){
						echo "<h2>Edit Subscription</h2>";
					} else {
						echo "<h2>Create Subscription</h2>";
					}
				?>

				<form action="SubscriptionDetails.php?ID=<?php echo $_GET['ID']; ?>" method="POST">
					
					<?php
						if(isset($Subscription['Status'])){
							echo '<input type="hidden" name="Action" value="Edit">';	
						} else {
							echo '<input type="hidden" name="Action" value="Add">';	
						}

						echo '<input type="hidden" name="ID" value=' . EscapeSQLEntry($Subscription['SubscriptionID']) . '>';	
					?>
						
				<hr>
					
					<!-- Subscription Fields -->	
					<div class="row">
						<div class="column">
							Client: 	<select name="Client" required="required">
											<?php 
												$ClientsQuery = "SELECT
																`ClientID`, 
																`ClientName`,
												  				`ClientSlug`
										  					FROM `Client`
										  				ORDER BY `ClientName` ASC";
												$stm = $DatabaseConnection->prepare($ClientsQuery);
												$stm->execute();
												$Clients = $stm->fetchAll();
												foreach ($Clients as $Client) {
													echo "<option value=" . $Client['ClientID'] . "";
														if($Subscription['ClientID'] == $Client['ClientID']){
															echo " selected='selected'";
														}
													echo ">" . $Client['ClientName'] . "</option>";
												}
											?>
										</select>
							</div>
							<div class="column">
								Subscription Name: <br>
									<textarea name="SubscriptionName" required="required"><?php echo $Subscription["SubscriptionName"]; ?></textarea>
							</div>
							<div class="column">
									Status: <br>
										<select name="Status" required="required">
											<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
											<option value="1" <?php if($Subscription['Status'] == "1") echo "selected='selected'"; ?>>Running</option>
											<option value="0" <?php if($Subscription['Status'] == "0") echo "selected='selected'"; ?>>Stopped</option>
										</select>
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Slip Type: 
										<select name="SlipType" required="required">
											<option value="TS" <?php if($Subscription["SlipType"] == "TS") echo " selected='selected'"; ?>>Professional Service</option>
											<option value="Expense" <?php if($Subscription["SlipType"] == "Expense") echo " selected='selected'"; ?>>Expense</option>
										</select>
							</div>
							<div class="column">
								Hours/Cost: <br>
									<input type="number" name="Cost" value="<?php echo isset($Subscription["Cost"]) ? $Subscription["Cost"]:0; ?>" min="0" step="any" style="color: black;">				
							</div>
							<div class="column">
								Quantity <i>(Expense only)</i>: <br>
									<input type="number" name="Quantity" value="<?php echo isset($Subscription["Quantity"]) ? $Subscription["Quantity"]:1; ?>" min="0" step="any" style="color: black;">	
							</div>
						</div>
							<hr>
						<div class="row">
							<div class="column">
									Start Date: <br>
										<input type="date" name="StartDate" value="<?php echo $Subscription["StartDate"]; ?>">
							</div>
							<div class="column">
									Subscription Recurrency: <br>
										<select name="Frequency">
											<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
											<option value="Daily" <?php if($Subscription['Frequency'] == "Daily") echo "selected='selected'"; ?>>Daily</option>
											<option value="Monthly" <?php if($Subscription['Frequency'] == "Monthly") echo "selected='selected'"; ?>>Monthly</option>
											<option value="Yearly" <?php if($Subscription['Frequency'] == "Yearly") echo "selected='selected'"; ?>>Yearly</option>
										</select>
							</div>
							<div class="column">
									Reccur On: <br>
										<select name="RecurrenceOn" id="RecurrenceOn">
											<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
											<option value="First" <?php if($Subscription['RecurrenceOn'] == "First") echo "selected='selected'"; ?>>On the First of the Cycle</option>
											<option value="Last" <?php if($Subscription['RecurrenceOn'] == "Last") echo "selected='selected'"; ?>>On the Last of the Cycle</option>
											<option value="Date" <?php if($Subscription['RecurrenceOn'] == "Date") echo "selected='selected'"; ?>>Specific Date</option>
										</select>
							</div>

							<div class="column" id="ReccurOnDate" style="display: none;">
									Reccur Date: <br>
										<input type="date" name="RecurrenceDate" value="<?php echo $Subscription['RecurrenceDate']; ?>">
							</div>
						</div>
							<hr>
								<?php
									if(isset($_GET['ID']) && $_GET['ID'] != ""){
										echo "<input type='checkbox' name='DeleteSubscription' value='Yes' id='DeleteSubscription'><label for='DeleteSubscription'>Delete Subscription?</label><br><i>Deleting Subscription will stop future Slip generation ONLY. It will <strong>NOT</strong> remove previously generated Slips</i><br>";
									}
								?>
							<br>
					<input type="submit" name="Submit" value="Save">
				</form>			
		</div>
	</section>
</div>
<script type="text/javascript">
	// Delete Subscription Alert
	$("#DeleteSubscription").change(function(){

		if($("#DeleteSubscription").is(":checked")){
			alert("Warning: this will delete the Subscription completely! Just make sure your deleting the right thing!");
		}

	});

    // Hide and Unhide Date
    $( document ).ready(function(){
    	if($("#RecurrenceOn").val() == "Date"){
    		$("#ReccurOnDate").css("display", "block");
    	} else {
    		$("#ReccurOnDate").css("display", "none");
    	}
    });

    $("#RecurrenceOn").change(function(){
    	if($("#RecurrenceOn").val() == "Date"){
    		$("#ReccurOnDate").css("display", "block");
    	} else {
    		$("#ReccurOnDate").css("display", "none");
    	}
    });
</script>
<?php 	/* Get Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>