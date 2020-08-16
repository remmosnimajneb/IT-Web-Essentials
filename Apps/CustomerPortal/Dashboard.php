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
* Customer Portal
* Login & Auth Page
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "CustomerPortal";
$AppletName = "PortalAccess";
$PageName = "Dashboard - Customer Portal";

/* Include System Functions */
require_once("../../InitSystem.php");

/* Reset Password */
if(isset($_POST['Action']) && $_POST['Action'] == "ResetAuthPassword" && ($_POST['Password'] == $_POST['ConfirmPassword'])){
	$Query = "UPDATE `ClientUser` SET `Password` = '" . password_hash(EscapeSQLEntry($_POST['Password']), PASSWORD_DEFAULT) . "' WHERE `ClientUserID` = '" . $_SESSION['ClientPortal_ClientUserID'] . "'";
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Message = "Password Reset!";
}

/* Get Client User Information */
$ClientUser = "SELECT * FROM `ClientUser` WHERE `ClientUserID` = '" . $_SESSION['ClientPortal_ClientUserID'] . "'";
$stm = $DatabaseConnection->prepare($ClientUser);
$stm->execute();
$ClientUser = $stm->fetchAll()[0];

/* Include Header */
require_once('Assets/Views/Header.php');
?>
<!-- Main -->
	<section id="main" class="wrapper alt">
		<div class="inner">
			<header class="major special">
				<p>Customer Portal</p>
				<h1>Dashboard - Welcome <?php echo $_SESSION['ClientPortal_Name']; ?></h1>
				<a href="Logout.php">Logout</a>
			</header>
				<p style="color: orange;"><?php if(isset($Message)) echo $Message; ?></p>
			<div class="row">
				<div class="column">
					<h3>My Info</h3>
					Name: <?php echo $ClientUser['Name']; ?>
						<br>
					Email: <a href="mailto:<?php echo $ClientUser['Email']; ?>"><?php echo $ClientUser['Email']; ?></a>
						<br><br>
					<h3>Reset Portal Password</h3>
					<form action="Dashboard.php" method="POST">
						<input type="hidden" name="Action" value="ResetAuthPassword">
						New Password: <input type="Password" name="Password" required="required">
							<br>
						Confirm Password: <input type="Password" name="ConfirmPassword" required="required">
							<br>
						<input type="submit" name="submit" value="Save">
					</form>
				</div>
				<?php if(GetClientUserPortalPermissions($_SESSION['ClientPortal_ClientUserID'], "ViewInvoices") == 1){ ?>
					<div class="column">
						<h3>Invoices</h3>
						<?php 
							$Invoices = "SELECT InvoiceID,InvoiceHash,InvoiceDate, IF(`PaymentStatus` = 1, \"PAID\", \"UNPAID\") AS PaymentStatus FROM `Invoice` WHERE `InvoiceStatus` = '1' AND `ClientID` = '" . $ClientUser['ClientID'] . "' LIMIT 4";
							$stm = $DatabaseConnection->prepare($Invoices);
							$stm->execute();
							$Invoices = $stm->fetchAll();
							if($stm->rowCount() == 0){
								?><p>No invoices have been generated for your account yet.</p><?php
							} else {
						?>
							<table class="alt">
							  <thead>
							    <tr>
							      <th scope="col">Invoice ID</th>
							      <th scope="col">Invoice Date</th>
							      <th scope="col">Payment Status</th>
							      <th scope="col">View</th>
							    </tr>
							  </thead>
							  <tbody>
							  	<?php
									foreach ($Invoices as $Invoice) {
										echo "<tr>";
											echo "<td data-label='Invoice ID'>" . $Invoice['InvoiceID'] . "</td>";
											echo "<td data-label='Invoice Date'>" . $Invoice['InvoiceDate'] . "</td>";
											echo "<td data-label='Payment Status'>" . $Invoice['PaymentStatus'] . "</td>";
											echo "<td data-label='View'><a href='/Apps/BillingPortal/Invoices/Invoice.php?ID=" . $Invoice['InvoiceHash'] . "'>View</a></td>";
										echo "</tr>";
									}
							  	?>
							  </tbody>
							</table>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php // V3.0 to Include Service Desk here ?>
		</div>
	</section>
<?php require_once('Assets/Views/Footer.php'); ?>