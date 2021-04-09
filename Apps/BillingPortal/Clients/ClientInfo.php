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
* Client Info
*/

/* Page Variables */
	$PageSecurityLevel = 1;
	$AppName = "BillingPortalAdmin";
	$PageName = "Client Info";

/* Include System Functions */
	require_once("../../../InitSystem.php");

/* Check the Client exists */
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

/* Add, Delete Notes */
	if($_POST['Action'] == "AddClientNote"){
		$Query = "INSERT INTO `ClientNote` (ClientID, UserID, Note, NoteDate) VALUES ('" . EscapeSQLEntry($_GET['Client']) . "', '" . EscapeSQLEntry($_SESSION['UserID']) . "', '" . EscapeSQLEntry($_POST['Note']) . "', '" . EscapeSQLEntry($_POST['NoteDate'])  . "')";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Note Added Successfully!";
	} else if($_GET["Action"] == "DeleteNote"){
		$Query = "DELETE FROM `ClientNote` WHERE `NoteID` = '" . EscapeSQLEntry($_GET['NoteID']) . "'";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		$Message = "Note Deleted!";
	}



/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<div id="main" style="width: 95vw;">
	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Client Info</h2>
		</header>
		<div class="content">
			
			<h2><?php echo $Client['ClientName']; ?></h2>
				<h3>Address: <?php echo $Client['StreetName'] . " " . $Client['City'] . ", " . $Client['State'] . " " . $Client['ZIP']; ?></h3>
				<h3>Email: <a href="mailto:<?php echo $Client['Email']; ?>"><?php echo $Client['Email']; ?></a></h3>
				<h3>Phone: <a href="tel:<?php echo $Client['Phone']; ?>"><?php echo $Client['Phone']; ?></a></h3>
				<hr>
				<div class="row">
					<div class="column">

						<h2>Recent Services</h2>
							<?php
								$Slips = "SELECT 
												S.`SlipID`,
												S.`StartDate`,
												S.`Description`,
												U.`UserID`,
												U.`Name`
											FROM 
												`Slip` AS S
													INNER JOIN
												`User` AS U
													ON S.`Consultant` = U.`UserID`
											WHERE `ClientID` = '" . $Client['ClientID'] . "' 
											ORDER BY `StartDate` DESC LIMIT 5";
						  		$stm = $DatabaseConnection->prepare($Slips);
								$stm->execute();
								$Slips = $stm->fetchAll();
								if($stm->rowCount() == 0){
									?><p>No recent work found!</p><?php
								} else {
							?>
								<table class="alt">
								  <thead>
								    <tr>
								      <th scope="col">ID</th>
								      <th scope="col">Consultant</th>
								      <th scope="col">Date</th>
								      <th scope="col" style="width: 60%">Description</th>
								      <th scope="col">View</th>
								    </tr>
								  </thead>
								  <tbody>
								  	<?php								
										foreach ($Slips as $Slip) {
											?>
												<tr>
													<td data-label='ID'><?php echo $Slip['SlipID']; ?></td>
													<td data-label='ID'><?php echo $Slip['Name']; ?></td>
													<td data-label='Date'><?php echo date_format(date_create($Slip['StartDate']), "m/d/Y"); ?></td>
													<td data-label='Description'><?php echo $Slip['Description']; ?></td>
													<td data-label='View'><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Slips/Slip.php?ID=<?php echo $Slip['SlipID']; ?>">View</td>
												</tr>
											<?php
										}
								  	?>
								  </tbody>
								</table>
							<?php }?>
						</div>

					<?php if( json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["BillingPortalAdmin"] == "1" ){  ?>
						<div class="column">
							<h2>Invoices</h2>
							<?php 
								$Invoices = "SELECT 
												I.`InvoiceID`, 
												I.`InvoiceHash`, 
												I.`InvoiceDate`, 
												CASE 
													WHEN I.`PaymentStatus` = 0 THEN \"<span style=\'color:red\'>Not Paid</span>\"
													WHEN I.`PaymentStatus` = 1 THEN \"<span style=\'color:white\'>Part Paid</span>\"
													WHEN I.`PaymentStatus` = 2 THEN \"<span style=\'color:green\'>Paid Full</span>\"
												END AS ComputedPaymentStatus
											FROM `Invoice` AS I
												WHERE I.`InvoiceStatus` = '1' 
													AND 
														I.`ClientID` = '" . $Client['ClientID'] . "' LIMIT 4";
								$stm = $DatabaseConnection->prepare($Invoices);
								$stm->execute();
								$Invoices = $stm->fetchAll();
								if($stm->rowCount() == 0){
									?><p>No invoices have been generated yet.</p><?php
								} else {
							?>
								<table class="alt">
								  <thead>
								    <tr>
								      <th scope="col">Invoice ID</th>
								      <th scope="col">Invoice Date</th>
								      <th scope="col">Payment Status</th>
								      <th scope="col">Details</th>
								      <th scope="col">View</th>
								    </tr>
								  </thead>
								  <tbody>
								  	<?php
										foreach ($Invoices as $Invoice) {
											?>
												<tr>
													<td data-label='ID'><?php echo $Invoice['InvoiceID']; ?></td>
													<td data-label='Date'><?php echo date_format(date_create($Invoice['InvoiceDate']), "m/d/Y"); ?></td>
													<td data-label='Payment Status'><?php echo $Invoice['ComputedPaymentStatus']; ?></td>
													<td data-label='View'><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Invoices/InvoiceDetails.php?ID=<?php echo $Invoice['InvoiceID']; ?>">Details</td>
													<td data-label='View'><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Invoices/Invoice.php?ID=<?php echo $Invoice['InvoiceHash']; ?>">View</td>
												</tr>
											<?php
										}
								  	?>
								  </tbody>
								</table>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<hr>
				<h2>Client Notes</h2>
				<?php
					$Notes = "SELECT * FROM `ClientNote` AS CN INNER JOIN `Client` AS C ON C.`ClientID` = CN.`ClientID` INNER JOIN `User` AS U ON U.`UserID` = CN.`UserID` WHERE CN.`ClientID` = '" . $Client['ClientID'] . "' ORDER BY CN.`NoteDate` ASC";
					$stm = $DatabaseConnection->prepare($Notes);
					$stm->execute();
					$Notes = $stm->fetchAll();

					/* No Notes */
					if($stm->rowCount() == 0){
						?><p>No notes found.</p><?php
					}
						/* Rest of Notes (If none, won't show) */
						foreach ($Notes as $Note) {
							?>
								<div style="border: 1px white solid; padding:15px;">
									<i><?php echo $Note['Name']; ?> @ <?php echo date_format(date_create($Note['NoteDate']), "m/d/Y, i:h:s"); ?></i>
									<br>
									<textarea disabled="disabled"><?php echo $Note['Note']; ?></textarea>
									<a href="ClientInfo.php?Action=DeleteNote&NoteID=<?php echo $Note['NoteID']; ?>&Client=<?php echo $_GET['Client']; ?>">Delete</a>
								</div><br>
							<?php
						}
				?>

				<hr>
				<form action="ClientInfo.php?Client=<?php echo $_GET['Client']; ?>" method="POST">
					<h4>Add Note</h4>
					<input type="hidden" name="Action" value="AddClientNote">
					<div class="row">
						<div class="column">
							Note: <textarea name="Note"></textarea>
						</div>
						<div class="column">
							Date: <input type="date" name="NoteDate" value="<?php echo date('Y-m-d', time()); ?>">
						</div>
					</div><br>
						<input type="submit" name="submit" value="Save">
				</form>
		</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>