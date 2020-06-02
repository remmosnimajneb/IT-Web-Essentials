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
* Show Clients
*/

/* Include System Functions */
require_once("../Functions.php");

/* Init System */
Init(false);

/* Set System */
$CurrentSystem = "BillingPortal";

$PageName = "Clients";

/* Include Header */
require_once('../../../SystemAssets/Views/BillingPortalHeader.php');
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
				  		$sql = "SELECT * FROM `Clients`";
				  		$stm = $DatabaseConnection->prepare($sql);
						$stm->execute();
						$records = $stm->fetchAll();
						foreach ($records as $row) {
							echo "<tr>";
								echo "<td data-label='ID'>" . $row['ClientID'] . "</td>";
								echo "<td data-label='Client Name'>" . $row['ClientName'] . "</td>";
								echo "<td data-label='Slug'>" . strtoupper($row['Slug']) . "</td>";
								echo "<td data-label='Address'>" . $row['StreetName'] . " " . $row['City'] . ", " . $row['State'] . " " . $row['ZIP'] . "</td>";
								echo "<td data-label='Email'><a href='mailto:" . $row['Email'] . "'>" . $row['Email'] . "</a></td>";
								echo "<td data-label='Phone'><a href='tel:" . $row['Phone'] . "'>" . $row['Phone'] . "</a></td>";
								echo "<td data-label='Edit'><a href='Client.php?Client=" . $row['Slug'] . "'>Edit</a></td>";
								echo "<td data-label='Info'><a href='ClientInfo.php?Client=" . $row['Slug'] . "'>Info</a></td>";
							echo "</tr>";
						}
				  	?>
				   
				  </tbody>
				</table>
			</div>
	</section>
</div>
<?php require_once('../../../SystemAssets/Views/BillingPortalFooter.php');  ?>