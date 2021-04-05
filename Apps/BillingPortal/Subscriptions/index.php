<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.2
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/*
* Subscriptions
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Subscriptions";

/* Include System Functions */
require_once("../../../InitSystem.php");

	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');

?>
<div id="main" style="width: 95vw;">

	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Subscriptions</h2>
		</header>
		<div class="content">
			<div class="row">
				<div class="column">
					<a href="SubscriptionDetails.php" class="button large">New Subscription</a>
				</div>
			</div>
				<hr>
			<!-- Subscriptions -->
				<h2>Subscriptions</h2>
				<?php 
					$Query = "SELECT 
								S.`SubscriptionID`, 
								S.`SubscriptionName`,
								S.`Cost`,
								S.`StartDate`,
								S.`Frequency`,
								S.`RecurrenceOn`,
								S.`RecurrenceDate`,
								S.`LastRunDate`,
								S.`Status`,
								C.`ClientID`, 
								C.`ClientSlug`,
								C.`ClientName`,
								CASE 
									WHEN S.`Status` = 0 THEN \"Stopped\"
									WHEN S.`Status` = 1 THEN \"Running\"
								END AS ComputedStatus

								FROM 
									`Subscription` AS S
										INNER JOIN
									`Client` AS C
										ON C.`ClientID` = S.`ClientID`
								ORDER BY S.`SubscriptionID` ASC";
			  		$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$Subscriptions = $stm->fetchAll();
					$RowCount = $stm->rowCount();
					if($RowCount == 0){
						echo "<h3>No Subscriptions Found!</h3>";
					} else {
				?>
				<table class="alt"> 
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">ID</th>
				      <th scope="col" style="width:70px">Client</th>
				      <th scope="col" style="width:90px">Subscription</th>
				      <th scope="col" style="width:70px">Cost</th>
				      <th scope="col" style="width:70px;">Start Date</th>
				      <th scope="col" style="width:70px">Frequency</th>
				      <th scope="col" style="width:70px">Last Run</th>
				      <th scope="col" style="width:70px">Status</th>
				      <th scope="col" style="width:90px">Details</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
						foreach ($Subscriptions as $S) {
							echo "<tr>";
								echo "<td data-label='ID'>" . $S['SubscriptionID'] . "</td>";
								echo "<td data-label='Client'>" . $S['ClientName'] . "</td>";
								echo "<td data-label='Subscription'>" . $S['SubscriptionName'] . "</td>";
								echo "<td data-label='Cost'>$" . number_format($S['Cost'], 2) . "</td>";
								echo "<td data-label='Start Date'>" . date("m/d/Y", strtotime($S['StartDate'])) . "</td>";
								echo "<td data-label='Frequency'>" . $S['Frequency'] . "</td>";
								echo "<td data-label='Last Run'>" . date("m/d/Y", strtotime($S['LastRunDate'])) . "</td>";
								echo "<td data-label='Status'>" . $S['ComputedStatus'] . "</td>";
								echo "<td data-label='Details'><a href='SubscriptionDetails.php?ID=" . $S['SubscriptionID'] . "'>View</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
				<?php } ?>
			</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>