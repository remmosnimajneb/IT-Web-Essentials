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
				  		$sql = "SELECT * FROM `Client`";
				  		$stm = $DatabaseConnection->prepare($sql);
						$stm->execute();
						$Clients = $stm->fetchAll();
						foreach ($Clients as $Client) {
							echo "<tr>";
								echo "<td data-label='ID'>" . $Client['ClientID'] . "</td>";
								echo "<td data-label='Client Name'>" . $Client['ClientName'] . "</td>";
								echo "<td data-label='Slug'>" . strtoupper($Client['ClientSlug']) . "</td>";
								echo "<td data-label='Address'>" . $Client['StreetName'] . " " . $Client['City'] . ", " . $Client['State'] . " " . $Client['ZIP'] . "</td>";
								echo "<td data-label='Email'><a href='mailto:" . $Client['Email'] . "'>" . $Client['Email'] . "</a></td>";
								echo "<td data-label='Phone'><a href='tel:" . $Client['Phone'] . "'>" . $Client['Phone'] . "</a></td>";
								echo "<td data-label='Edit'><a href='Client.php?Client=" . $Client['ClientID'] . "'>Edit</a></td>";
								echo "<td data-label='Info'><a href='ClientInfo.php?Client=" . $Client['ClientID'] . "'>Info</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
			</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>