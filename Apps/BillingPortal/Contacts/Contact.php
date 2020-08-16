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
* Edit Contact
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Contacts";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* Now if we are adding, editing or deleting - let's run those */
if(isset($_POST)){
	/* Delete */
	if($_POST['DeleteContact'] == "Yes"){
		$Query = "DELETE FROM `ClientUser` WHERE `ClientUserID` = " . EscapeSQLEntry($_POST['ContactID']);
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Contact Removed!";
	} else 

	/* Edit Basic */
	if($_POST['Action'] == "EditContactInfo"){
		$Query = "UPDATE `ClientUser` SET `Name` = '" . EscapeSQLEntry($_POST['Name']) . "', `PositionTitle` = '" . EscapeSQLEntry($_POST['PositionTitle']) . "', `Email` = '" . EscapeSQLEntry($_POST['Email']) . "', `StreetName` = '" . EscapeSQLEntry($_POST['StreetName']) . "', `City` = '" . EscapeSQLEntry($_POST['City']) . "', `State` = '" . EscapeSQLEntry($_POST['State']) . "', `ZIP` = '" . EscapeSQLEntry($_POST['ZIP']) . "', `HomePhone` = '" . EscapeSQLEntry($_POST['HomePhone']) . "', `CellPhone` = '" . EscapeSQLEntry($_POST['CellPhone']) . "', `FaxNumber` = '" . EscapeSQLEntry($_POST['FaxNumber']) . "', `Notes` = '" . EscapeSQLEntry($_POST['Notes']) . "' WHERE `ClientUserID` = '" . $_POST['ContactID'] . "'";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Contact Edited Succesfully!";
	} else 

	/* Edit Security Info */
	if($_POST['Action'] == "EditContantSecurityInfo" && (GetUserSecurityLevel($_SESSION['UserID']) == 2)){
		//Check if checkbox for ReceivesInvoices is clicked
			$ReceivesInvoices = 0;
			if(!empty($_POST['ReceivesInvoices'])){ $ReceivesInvoices = 1; }
		//Check if checkbox for PortalAccessAllowed is clicked
			$PortalAccessAllowed = 0;
			if(!empty($_POST['PortalAccessAllowed'])){ $PortalAccessAllowed = 1; }
		//Check if checkbox for CustomerPortalCanViewInvoices is clicked
			$CustomerPortalCanViewInvoices = 0;
			if(!empty($_POST['CustomerPortalCanViewInvoices'])){ $CustomerPortalCanViewInvoices = 1; }
		//Check if checkbox for CustomerPortalCanSubmitTickets is clicked
			$CustomerPortalCanSubmitTickets = 0;
			if(!empty($_POST['CustomerPortalCanSubmitTickets'])){ $CustomerPortalCanSubmitTickets = 1; }
		$Query = "UPDATE `ClientUser` SET `ClientID` = '" . EscapeSQLEntry($_POST['ClientID']) . "', `ReceivesInvoices` = '" . $ReceivesInvoices . "', `PortalAccessAllowed` = '" . $PortalAccessAllowed . "', `PortalCanSubmitTickets` = '" . $CustomerPortalCanSubmitTickets . "', `PortalCanViewInvoices` = '" . $CustomerPortalCanViewInvoices . "' WHERE `ClientUserID` = '" . $_POST['ContactID'] . "'";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Security Information Updated!";
	} else 

	/* Reset Password */
	if($_POST['Action'] == "ResetPassword" && (GetUserSecurityLevel($_SESSION['UserID']) == 2) && ($_POST['Password'] == $_POST['PasswordConfirm'])){
		$Query = "UPDATE `ClientUser` SET `Password` = '" . password_hash(EscapeSQLEntry($_POST['Password']), PASSWORD_DEFAULT) . "' WHERE `ClientUserID` = '" . $_POST['ContactID'] . "'";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Password Reset!";
	}
}

/* If not editing, let's check we have the right Contact here */

/* Check ContactID is set */
if(empty($_GET['ContactID']) || $_GET['ContactID'] == ""){
	header('Location: index.php');
}

$Contact = "SELECT * FROM `ClientUser` WHERE `ClientUserID` = '" . EscapeSQLEntry($_GET['ContactID']) . "'";
$stm = $DatabaseConnection->prepare($Contact);
$stm->execute();
$Contact = $stm->fetchAll()[0];

/* If fake ID */
if($stm->rowCount() == 0){
	header('Location: index.php');
}

/* Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Edit Contact</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>

				<form action="Contact.php?ContactID=<?php echo $Contact['ClientUserID']; ?>" method="POST">
					<input type="hidden" name="Action" value="EditContactInfo">
					<input type="hidden" name="ContactID" value="<?php echo $Contact['ClientUserID']; ?>">
					<h3>General Information</h3>
					<div class="row">
						<div class="column">
							Name: <input type="text" name="Name" required="required" value="<?php echo $Contact['Name']; ?>">
						</div>
						<div class="column">
							Position/Title: <input type="text" name="PositionTitle" required="required" value="<?php echo $Contact['PositionTitle']; ?>">
						</div>
					</div>
					<div class="row">
						<div class="column">
							Email: <input type="email" name="Email" required="required" value="<?php echo $Contact['Email']; ?>">
						</div>
					</div><br>
					<h3>Contact Info</h3>
					<div class="row">
						<div class="column">
							Street Name: <input type="text" name="StreetName" value="<?php echo $Contact['SteetName']; ?>">
						</div>
						<div class="column">
							City: <input type="text" name="City" value="<?php echo $Contact['City']; ?>">
						</div>
						<div class="column">
							State: <input type="text" name="State" value="<?php echo $Contact['State']; ?>">
						</div>
						<div class="column">
							ZIP: <input type="text" name="ZIP" value="<?php echo $Contact['ZIP']; ?>">
						</div>
					</div>
					<div class="row">
						<div class="column">
							Home Phone: <input type="text" name="HomePhone" value="<?php echo $Contact['HomePhone']; ?>">
						</div>
						<div class="column">
							Cell Phone: <input type="text" name="CellPhone" value="<?php echo $Contact['CellPhone']; ?>">
						</div>
						<div class="column">
							Fax Number: <input type="text" name="FaxNumber" value="<?php echo $Contact['FaxNumber']; ?>">
						</div>
					</div><br><br>
					<div class="row">
						<div class="column">
							Notes: <textarea name="Notes"><?php echo $Contact['Notes']; ?></textarea>
						</div>
					</div><br>
					<input type='checkbox' name='DeleteContact' value='Yes' id='DeleteContact'>
						<label for='DeleteContact'>Delete?</label>
					<br><input type="submit" name="submit" value="Save">
				</form>
				<?php 
					/* If user is Admin */
					if(GetUserSecurityLevel($_SESSION['UserID']) == 2){
				?>
					<hr>
					<h3>Admin Only - Security Information</h3>
					<div class="row">
						<div class="column" style="border: 1px white solid; padding:15px;margin:15px;">
							<form action="Contact.php?ContactID=<?php echo $Contact['ClientUserID']; ?>" method="POST">
								<input type="hidden" name="Action" value="EditContantSecurityInfo">
								<input type="hidden" name="ContactID" value="<?php echo $Contact['ClientUserID']; ?>">
								<div class="row">
									<div class="column">
										Client: 	<select name="ClientID" required="required">
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
															if($Contact['ClientID'] == $Client['ClientID']){
																echo " selected='selected'";
															}
														echo ">" . $Client['ClientName'] . "</option>";
													}
												?>
											</select>
									</div>
								</div><br>
								<div class="row">
									<div class="column">
										<input type='checkbox' name='ReceivesInvoices' value='Yes' id='ReceivesInvoices' <?php if($Contact['ReceivesInvoices'] == 1) echo 'checked="checked"'; ?>>
										<label for="ReceivesInvoices">Receives Invoices?</label><br>
									</div>
									<div class="column">
										<input type='checkbox' name='PortalAccessAllowed' value='Yes' id='PortalAccessAllowed' <?php if($Contact['PortalAccessAllowed'] == 1) echo 'checked="checked"'; ?>>
										<label for="PortalAccessAllowed">Portal Access Allowed?</label><br>
									</div>
								</div>
								<div class="row">
									<?php
										if(GetSysConfig("CustomerPortalCanViewInvoices")){
											?>
											<div class="column">
												<input type='checkbox' name='CustomerPortalCanViewInvoices' value='Yes' id='CustomerPortalCanViewInvoices' <?php if($Contact['PortalCanViewInvoices'] == 1) echo 'checked="checked"'; ?>>
													<label for="CustomerPortalCanViewInvoices">Can view invoices in the Portal? 
														<i>Note: This will only apply if user has Portal Access Allowed</i>
													</label>
											</div>
											<?php
										}
									?>
									<?php
										if(GetSysConfig("CustomerPortalCanSubmitTickets")){
											?>
											<div class="column">
												<input type='checkbox' name='CustomerPortalCanSubmitTickets' value='Yes' id='CustomerPortalCanSubmitTickets' <?php if($Contact['PortalCanSubmitTickets'] == 1) echo 'checked="checked"'; ?>>
													<label for="CustomerPortalCanSubmitTickets">Can Submit Support Tickets in the Portal? 
														<i>Note: This will only apply if user has Portal Access Allowed</i>
													</label>
											</div>
											<?php
										}
									?>
								</div><br>
								<input type="submit" name="submit" value="Save">
							</form>
						</div>
						<div class="column" style="border: 1px white solid; padding:15px;margin:15px;">
							<form action="Contact.php?ContactID=<?php echo $Contact['ClientUserID']; ?>" method="POST">
								<input type="hidden" name="Action" value="ResetPassword">
								<input type="hidden" name="ContactID" value="<?php echo $Contact['ClientUserID']; ?>">
								<div class="row">
									<div class="column">
										Reset Password: <input type="text" name="Password">
									</div>
									<div class="column">
										Confirm Password: <input type="text" name="PasswordConfirm">
									</div>
								</div><br>
								<input type="submit" name="submit" value="Save">
							</form>
						</div>
					</div>
			<?php } ?>
			</div>
		</section>
	</div>
<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>