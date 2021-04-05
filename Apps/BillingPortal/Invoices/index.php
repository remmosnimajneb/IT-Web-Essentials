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
* Invoices Overview
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Billing Portal";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* Configue Filter Options */

	$WHEREClause = "";

	/* 1. Client */
		$ClientName = "All";
		if(isset($_GET['Client']) && $_GET['Client'] != ""){
			$ClientName = $_GET['Client'];
		}
		if($ClientName != "All"){
			if($WHEREClause != ""){
				$WHEREClause .= " AND ";
			}
			$WHEREClause .= " I.`ClientID` = '" . EscapeSQLEntry($ClientName) . "'";
		}
	/* 2. Invoice Type */
		$InvoiceType = "All";
		if(isset($_GET['InvoiceType']) && $_GET['InvoiceType'] != ""){
			$InvoiceType = $_GET['InvoiceType'];
		}
		if($InvoiceType != "All"){
			if($WHEREClause != ""){
				$WHEREClause .= " AND ";
			}
			$WHEREClause .= " I.`InvoiceType` = '" . EscapeSQLEntry($InvoiceType) . "'";
		}
	/* 3. Invoice Status */
		$InvoiceStatus = "All";
		if(isset($_GET['InvoiceStatus']) && $_GET['InvoiceStatus'] != ""){
			$InvoiceStatus = $_GET['InvoiceStatus'];
		}
		if($InvoiceStatus != "All"){
			if($WHEREClause != ""){
				$WHEREClause .= " AND ";
			}
			$WHEREClause .= " I.`InvoiceStatus` = '" . EscapeSQLEntry($InvoiceStatus) . "'";
		}
	/* 4. Payment Status */
		$PaymentStatus = "All";
		if(isset($_GET['PaymentStatus']) && $_GET['PaymentStatus'] != ""){
			$PaymentStatus = $_GET['PaymentStatus'];
		}
		if($PaymentStatus != "All"){
			if($WHEREClause != ""){
				$WHEREClause .= " AND ";
			}
			$WHEREClause .= " I.`PaymentStatus` = '" . EscapeSQLEntry($PaymentStatus) . "'";
		}
		
	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<div id="main" style="width: 95vw;">
	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Invoices</h2>
		</header>
		<div class="content">
			<div class="row">
				<div class="column">
					<a href="InvoiceDetails.php" class="button large">New Invoice</a>
				</div>
				<div class="column">
					<a id="OpenModal" class="button large">New Worksheet</a>
				</div>
			</div>
				<hr>
			<!-- Filters + Options for Search -->
				<button id="FilterToggle">Filters</button>
				<section id="Filter" style="display: none;">
					<h3>Filter Invoices</h3>
					<form action="index.php" method="GET">
						Client: 	<select name="Client" style="width: 35%;">
											<option value="All">All Clients</option>
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
												if($Client['ClientID'] == $ClientName){
													echo " selected='selected'";
												}
											echo ">" . $Client['ClientName'] . "</option>";
										}
									?>
								</select><br>
						<div class="row">
							<div class="column">
								Invoice Type:
									<select name="InvoiceType">
										<option value="All">All</option>
										<option value="Flat">Flat Rate</option>
										<option value="Hourly">Hourly</option>
									</select>
							</div>
							<div class="column">
								Invoice Status:
									<select name="InvoiceStatus">
										<option value="All">All</option>
										<option value="0">Not Sent</option>
										<option value="1">Sent</option>
									</select>
							</div>
							<div class="column">
								Payment Status:
									<select name="PaymentStatus">
										<option value="All">All</option>								
										<option value="0">Not Paid</option>
										<option value="1">Partial Payment</option>
										<option value="2">Paid in Full</option>
									</select>
							</div>
						</div>
							<br>
							<input type="submit" name="submit" value="Filter">
					</form>
				</section><br><br>

			<!-- Invoices -->
				<h2>Invoices</h2>
				<?php 
					$Query = "SELECT 
								I.`InvoiceID`, 
								I.`ClientID`,
								C.`ClientSlug`, 
								I.`InvoiceType`, 
								I.`InvoiceDate`,
								C.`ClientName`,
								ComputeInvoiceTotal(InvoiceID) AS Inv_Total,
								IF(I.`InvoiceStatus` = 1, \"Locked\", \"Unlocked\") AS InvoiceStatus, 
								CASE 
									WHEN I.`PaymentStatus` = 0 THEN \"Not Paid\"
									WHEN I.`PaymentStatus` = 1 THEN \"Part Paid\"
									WHEN I.`PaymentStatus` = 2 THEN \"Paid Full\"
								END AS PaymentStatus

								FROM 
									`Invoice` AS I
										INNER JOIN
									`Client` AS C
										ON C.`ClientID` = I.`ClientID`";
						if($WHEREClause != ""){
							$Query .= " WHERE " . $WHEREClause;
						}
							$Query .= " ORDER BY I.`InvoiceDate` DESC";
			  		$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$records = $stm->fetchAll();
					$RowCount = $stm->rowCount();
					if($RowCount == 0){
						echo "<h3>No Invoices Found!</h3>";
					} else {
				?>
				<table class="alt"> 
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">ID</th>
				      <th scope="col" style="width:70px">Client</th>
				      <th scope="col" style="width:70px">Invoice Type</th>
				      <th scope="col" style="width:90px">Date</th>
				      <th scope="col" style="width: 70px;">Total</th>
				      <th scope="col" style="width:70px">Invoice Status</th>
				      <th scope="col" style="width:70px">Payment Status</th>
				      <th scope="col" style="width:90px">Details</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
						foreach ($records as $row) {
							echo "<tr>";
								echo "<td data-label='ID'>" . $row['InvoiceID'] . "</td>";
								echo "<td data-label='Client'>" . $row['ClientName'] . "</td>";
								echo "<td data-label='Invoice Type'>" . $row['InvoiceType'] . "</td>";
								echo "<td data-label='Date'>" . date("m/d/Y", strtotime($row['InvoiceDate'])) . "</td>";
								echo "<td data-label='Total'>$" . number_format($row['Inv_Total'], 2) . "</td>";
								echo "<td data-label='Invoice Status'>" . $row['InvoiceStatus'] . "</td>";
								echo "<td data-label='Payment Status'>";
									if($row['PaymentStatus'] != "Paid Full"){
										echo "<span style='color:red'>";
									} else {
										echo "<span style='color:green'>";
									}
								echo $row['PaymentStatus'] . "</span></td>";
								echo "<td data-label='Details'><a href='InvoiceDetails.php?ID=" . $row['InvoiceID'] . "'>View</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
				<?php } ?>
			</div>
			<!-- The Modal -->
			<div id="modal" class="modal">
			  <!-- Modal content -->
			  <div class="modal-content">
				  <div class="modal-header">
				    <span class="close">&times;</span>
				    <h2>New Worksheet</h2>
				  </div>
				  <div class="modal-body">
				  	<br>
				    <form action="Worksheet.php" method="GET">
						1. Client:
							<select name="ClientID">
								<?php
									$sql = "SELECT * FROM `Client`";
									$stm = $DatabaseConnection->prepare($sql);
									$stm->execute();
									$records = $stm->fetchAll();
									foreach ($records as $row) {
										echo "<option value=" . $row['ClientID'] . ">" . $row['ClientName'] . "</option>";
									}
								?>
							</select>
						<br><br>
						2. Invoice Start Date:
						<input type="date" name="StartDate">
						<br><br>
						3. Invoice End Date:
						<input type="date" name="EndDate"><br><br>
						<input type="submit" name="submit" value="Build">
					</form>
				  </div>
				  <div class="modal-footer">
				    <h3></h3>
				  </div>
				</div>			    			
			  </div>
	</section>
</div>
<script type="text/javascript">
	$(function(){
	    $('#FilterToggle').on('click', function(){
	       $( "#Filter" ).slideToggle();
	    });
	});
	// Show Modal
		// Get the modal
		var modal = document.getElementById("modal");
		// Get the button that opens the modal
		var open = document.getElementById("OpenModal");
		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];
		// When the user clicks on the button, open the modal
		open.onclick = function() {
		  modal.style.display = "block";
		}
		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		} 
	</script>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>