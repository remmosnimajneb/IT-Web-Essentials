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
* Quotes
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Quotes";

/* Include System Functions */
require_once("../../../InitSystem.php");

	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');

?>
<div id="main" style="width: 95vw;">

	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Quotes</h2>
		</header>
		<div class="content">
			<div class="row">
				<div class="column">
					<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/QuoteDetails.php" class="button large">New Quote</a>
				</div>
			</div>
				<hr>
			<!-- Subscriptions -->
				<h2>Quotes</h2>
				<?php 
					$Query = "SELECT 
								Q.`QuoteID`, 
								Q.`Hash`,
								Q.`Name`,
								Q.`Approved`,
								Q.`Date`,
								C.`ClientID`, 
								C.`ClientName`,
								CASE 
									WHEN Q.`Approved` = 0 THEN \"<span style=\'color:red\'>Not Approved</span>\"
									WHEN Q.`Approved` = 1 THEN \"<span style=\'color:green\'>Approved</span>\"
								END AS ApprovalStatusNice

								FROM 
									`Quote` AS Q
										INNER JOIN
									`Client` AS C
										ON C.`ClientID` = Q.`ClientID`
								ORDER BY Q.`QuoteID` ASC";
			  		$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$Quotes = $stm->fetchAll();
					if($stm->rowCount() == 0){
						?><h3>No Price Quotes found!</h3><?php
					} else {
				?>
					<table class="alt"> 
					  <thead>
					    <tr>
					      <th scope="col" style="width:35px">ID</th>
					      <th scope="col" style="width:70px">Client</th>
					      <th scope="col" style="width:90px">Name</th>
					      <th scope="col" style="width:70px">Date</th>
					      <th scope="col" style="width:70px">Status</th>
					      <th scope="col" style="width:35px">Edit</th>
					      <th scope="col" style="width:35px">View</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php
							foreach ($Quotes as $Q) {
							?>
								<tr>
									<td data-label='ID'><?php echo $Q['QuoteID']; ?></td>
									<td data-label='Client'><?php echo $Q['ClientName']; ?></td>
									<td data-label='Name'><?php echo $Q['Name']; ?></td>
									<td data-label='Date'><?php echo date("m/d/Y", strtotime($Q['Date'])); ?></td>
									<td data-label='Status'><?php echo $Q['ApprovalStatusNice']; ?></td>
									<td data-label='Status'><a href="QuoteDetails.php?ID=<?php echo $Q['QuoteID']; ?>">Edit</a></td>
									<td data-label='View'><a href="Quote.php?ID=<?php echo $Q['Hash']; ?>">View</a></td>
								</tr>
							<?php } ?>
					  </tbody>
					</table>
				<?php } ?>
			</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>