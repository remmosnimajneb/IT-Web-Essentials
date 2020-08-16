<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Allow the user to Filter for Time Slips
* Default is show the User their Slips for Today
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalUser";
$PageName = "Slips";

/* Include System Functions */
require_once("../../../InitSystem.php");

require_once(SYSPATH . '/Assets/Views/Header.php');


/* Configue Filter Options */
	
	$WHEREClause = "";
	
	/* 1. Consultant */
		$Consultant = $_SESSION['ConsultantSlug'];
		if(isset($_GET['Consultant']) && $_GET['Consultant'] != ""){
			$Consultant = $_GET['Consultant'];
		}

		if($Consultant != "All"){
			$WHEREClause .= " AND `Consultant` = '" . EscapeSQLEntry($Consultant) . "'";
		}
		
	/* 2. Client */
		$ClientID = "All";
		if(isset($_GET['Client']) && $_GET['Client'] != ""){
			$ClientID = $_GET['Client'];
		}

		if($ClientID != "All"){
			$WHEREClause .= " AND `ClientName` = '" . EscapeSQLEntry($ClientID) . "'";
		}

	/* 3. Date */
		$Date = date('Y-m-d');
		if(isset($_GET['Date']) && $_GET['Date'] != ""){
			$Date = date_format(date_create(EscapeSQLEntry($_GET['Date'])), "Y-m-d");
		}

		$WHEREClause .= " AND `StartDate` = '" . EscapeSQLEntry($Date) . "'";

	/* 4. Hours */
		$Hours = "All";
		if(isset($_GET['Hours']) && $_GET['Hours'] != ""){
			$Hours = $_GET['Hours'];
		}

		if($Hours != "All"){
			$WHEREClause .= " AND `Hours` = '" . EscapeSQLEntry($Hours) . "'";
		}

	/* 4. DNB Hours */
		$DNBHours = "All";
		if(isset($_GET['DNBHours']) && $_GET['DNBHours'] != ""){
			$DNBHours = $_GET['DNBHours'];
		}

		if($DNBHours != "All"){
			$WHEREClause .= " AND `DNB` = '" . EscapeSQLEntry($DNBHours) . "'";
		}

?>
<div id="main" style="width: 95vw;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Time Slips & Expenses</h2>
		</header>
		<div class="content">
			<a href="Slip.php" class="button large">New Slip</a>
				<hr>
			<!-- Filters + Options for Search -->
				<button id="FilterToggle">Filters</button>
				<section id="Filter" style="display: none;">
					<h3>Filter Slips</h3>
					<form action="index.php" method="GET">
						Consultant: <select name="Consultant" style="width: 35%;">
										<option value="All">All Consultants</option>
								<?php 
									$UsersQuery = "SELECT 
													`Name`,
									  				`ConsultantSlug`
							  					FROM `User`";
									$stm = $DatabaseConnection->prepare($UsersQuery);
									$stm->execute();
									$Users = $stm->fetchAll();
									foreach ($Users as $User) {
										echo "<option value=" . $User['ConsultantSlug'] . "";
											if($User['ConsultantSlug'] == $Consultant){
												echo " selected='selected'";
											}
										echo ">" . $User['Name'] . "</option>";
									}
								?>
							</select>
						Client: 	<select name="Client" style="width: 35%;">
										<option value="All">All Clients</option>
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
											if($Client['ClientID'] == $ClientID){
												echo " selected='selected'";
											}
										echo ">" . $Client['ClientName'] . "</option>";
									}
								?>
							</select><br>
						Date:		<input type="date" name="Date" value="<?php echo $Date; ?>">
						Hours: 		<input type="number" name="Hours" min="0" step="0.01" value="<?php if($Hours != "All") echo $Hours; ?>" style="color:black;">						
						DNB Hours: 	<input type="number" name="DNBHours" min="0" step="0.01" value="<?php if($DNBHours != "All") echo $DNBHours; ?>" style="color:black;">
							<input type="submit" name="submit" value="Filter">
					</form>
				</section><br><br>
			
			<!-- Previous and Next Day Toggles -->
				<div class="row">
					<div class="column"><a href="<?php echo 'index.php?Consultant=' . $Consultant . '&Client=' . $ClientName . '&Date=' . date('Y-m-d', strtotime("-1 day", strtotime($Date))) . '&Hours=' . $Hours . '&DNBHours=' . $DNBHours; ?>"><i class="fas fa-arrow-circle-left"></i></a></div>
					<div class="column"><h2 style="text-align: center;">Slips for: <?php echo date_format(date_create($Date), "m/d/Y"); ?></h2></div>
				  	<div class="column"><a href="<?php echo 'index.php?Consultant=' . $Consultant . '&Client=' . $ClientName . '&Date=' . date('Y-m-d', strtotime("+1 day", strtotime($Date))) . '&Hours=' . $Hours . '&DNBHours=' . $DNBHours; ?>"><i class="fas fa-arrow-circle-right"></i></a></div>
				</div>

			<!-- Slips and Expenses -->

				<h2>Time Slips</h2>
				<?php 
					$Query = "SELECT * FROM `Slip` AS S INNER JOIN `Client` AS C ON C.`ClientID` = S.`ClientID` WHERE `TSType` = 'TS' " . $WHEREClause . "";
			  		$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$records = $stm->fetchAll();
					$RowCount = $stm->rowCount();
					if($RowCount == 0){
						echo "<h3>No Time Slips Found!</h3>";
					} else {
				?>
				<table class="alt"> 
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">Slip ID</th>
				      <th scope="col" style="width:70px">Consultant</th>
				      <th scope="col" style="width:70px">Client Name</th>
				      <th scope="col" style="width:90px">Date</th>
				      <th scope="col" style="width:70px">Hours</th>
				      <th scope="col" style="width:70px">DNB</th>
				      <th scope="col" style="width:280px">Description</th>
				      <th scope="col" style="width:90px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
						foreach ($records as $row) {
							echo "<tr>";
								echo "<td data-label='Slip ID'>" . $row['SlipID'] . "</td>";
								echo "<td data-label='Consultant'>" . ucfirst($row['Consultant']) . "</td>";
								echo "<td data-label='Client Name'>" . strtoupper($row['ClientName']) . "</td>";
								echo "<td data-label='Date'>" . $row['StartDate'] . "</td>";
								echo "<td data-label='Hours'>" . $row['Hours'] . "</td>";
								echo "<td data-label='DNB'>" . $row['DNB'] . "</td>";
								echo "<td data-label='Description'>" . $row['Description'] . "</td>";
								echo "<td data-label='Edit'><a href='Slip.php?ID=" . $row['SlipID'] . "'>Edit</a></td>";
							echo "</tr>";
						}

				  	?>
				  </tbody>
				</table>
				<?php } ?>

				<h2>Expenses</h2>
				<?php 
					$Query = "SELECT * FROM `Slip` AS S INNER JOIN `Client` AS C ON C.`ClientID` = S.`ClientID` WHERE `TSType` = 'Expense' " . $WHEREClause . "";
			  		$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$records = $stm->fetchAll();
					$RowCount = $stm->rowCount();
					if($RowCount == 0){
						echo "<h3>No Expenses Found!</h3>";
					} else {
				?>
				<table class="alt">
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">Slip ID</th>
				      <th scope="col" style="width:70px">Consultant</th>				      
				      <th scope="col" style="width:70px">Client Name</th>
				      <th scope="col" style="width:90px">Date</th>
				      <th scope="col" style="width:70px">Qty</th>
				      <th scope="col" style="width:70px">Amount Per</th>
				      <th scope="col" style="width:280px">Description</th>
				      <th scope="col" style="width:90px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
						foreach ($records as $row) {
							echo "<tr>";
								echo "<td data-label='Slip ID'>" . $row['SlipID'] . "</td>";
								echo "<td data-label='Consultant'>" . ucfirst($row['Consultant']) . "</td>";								
								echo "<td data-label='Client Name'>" . strtoupper($row['ClientName']) . "</td>";
								echo "<td data-label='Date'>" . $row['StartDate'] . "</td>";
								echo "<td data-label='Hours'>" . $row['Quantity'] . "</td>";
								echo "<td data-label='DNB'>" . $row['Price'] . "</td>";
								echo "<td data-label='Description'>" . $row['Description'] . "</td>";
								echo "<td data-label='Edit'><a href='Slip.php?ID=" . $row['SlipID'] . "'>Edit</a></td>";
							echo "</tr>";
						}
				  	?>
				   
				  </tbody>
				</table>
				<?php } ?>
			</div>

	</section>
</div>
<script type="text/javascript">
	$(function(){
	    $('#FilterToggle').on('click', function(){
	       $( "#Filter" ).slideToggle();
	    });
	});
</script>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>