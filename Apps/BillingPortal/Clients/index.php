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
* Show Clients
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Clients";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* Get Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<div id="main" style="width: 95vw;">
	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Clients</h2>
		</header>
		<div class="content">
			<a href="Client.php" class="button large">New Client</a>
				<hr>
				<h2>Clients</h2>
				<table class="alt">
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">ID</th>
				      <th scope="col" style="width:70px">Client Name</th>
				      <th scope="col" style="width:70px">Client Slug</th>
				      <th scope="col" style="width:90px">Address</th>
				      <th scope="col" style="width:75px">Email</th>
				      <th scope="col" style="width:70px">Phone</th>
				      <th scope="col" style="width:30px">Edit</th>
				      <th scope="col" style="width:30px">Info</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all Clients
				  		$Query = "SELECT * FROM `Client` ORDER BY `ClientName` ASC";
				  		$stm = $DatabaseConnection->prepare($Query);
						$stm->execute();
						$Clients = $stm->fetchAll();
						if($stm->rowCount() > 0){

							foreach ($Clients as $Client) {
								?>
									<tr>
										<td data-label='ID'><?php echo $Client['ClientID']; ?></td>
										<td data-label='Client Name'><?php echo $Client['ClientName']; ?></td>
										<td data-label='Slug'><?php echo strtoupper($Client['ClientSlug']); ?></td>
										<td data-label='Addresss'><?php echo $Client['StreetName'] . " " . $Client['City'] . ", " . $Client['State'] . " " . $Client['ZIP']; ?></td>
										<td data-label='Email'><a href='mailto:<?php echo $Client['Email']; ?>'><?php echo $Client['Email']; ?></a></td>
										<td data-label='Phone'><a href='tel:<?php echo $Client['Phone']; ?>'><?php echo $Client['Phone']; ?></a></td>
										<td data-label='Edit'><a href='<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Clients/Client.php?Client=<?php echo $Client['ClientID']; ?>'>Edit</a></td>
										<td data-label='Info'><a href='<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Clients/ClientInfo.php?Client=<?php echo $Client['ClientID']; ?>'>Info</a></td>
									</tr>
								<?php
							}

						} else {
							?><p>No Clients found!</p><?php
						}
				  	?>
				  </tbody>
				</table>
			</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>