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
* Contacts
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Contacts";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* Add New */
if(isset($_POST)){
	//1. Add New Slug
	if($_POST['Action'] == "AddNew"){

		$Add = "INSERT INTO `ClientUser` (Name, PositionTitle, Email, ClientID) VALUES ('" . EscapeSQLEntry($_POST['Name']) . "', '" . EscapeSQLEntry($_POST['PositionTitle']) . "', '" . EscapeSQLEntry($_POST['Email']) . "', '" . EscapeSQLEntry($_POST['ClientID']) . "')";
		$stm = $DatabaseConnection->prepare($Add);
		$stm->execute();
		$Message = "Added Contact!";
	}
}

/* Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Contacts</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<table>
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">Name</th>
				      <th scope="col" style="width:35px">Position</th>
				      <th scope="col" style="width:35px">Email</th>
				      <th scope="col" style="width:35px">Cell Phone</th>
				      <th scope="col" style="width:35px">Client</th>
				      <th scope="col" style="width:35px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all Contacts
				  		$Query = "SELECT 
				  				C.`ClientUserID`,
				  				C.`Name`,
				  				C.`PositionTitle`,
				  				C.`Email`,
				  				C.`CellPhone`,
				  				CL.`ClientSlug`,
				  				CL.`ClientName`
				  				FROM 
				  					`ClientUser` AS C 
				  						INNER JOIN 
				  					`Client` AS CL 
				  						ON 
				  					CL.`ClientID` = C.`ClientID`";
				  		$stm = $DatabaseConnection->prepare($Query);
						$stm->execute();
						$Contacts = $stm->fetchAll();
						foreach ($Contacts as $Contact) {
							echo "<tr>";
								echo "<td data-label='Name'>" . $Contact['Name'] . "</td>";
								echo "<td data-label='Position'>" . $Contact['PositionTitle'] . "</td>";
								echo "<td data-label='Email'>" . $Contact['Email'] . "</td>";
								echo "<td data-label='Cell Phone'>" . $Contact['CellPhone'] . "</td>";
								echo "<td data-label='Client'>" . strtoupper($Contact['ClientName']) . "</td>";
								echo "<td data-label='Edit'><a href='Contact.php?ContactID=" . $Contact['ClientUserID'] . "'>Edit</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
				<h3>New Contact</h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="AddNew">
					<div class="row">
						<div class="column">
							Name: <input type="text" name="Name" required="required">
						</div>
						<div class="column">
							Position/Title: <input type="text" name="PositionTitle" required="required">
						</div>
					</div>
					<div class="row">
						<div class="column">
							Email: <input type="email" name="Email" required="required">
						</div>
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
											echo "<option value=" . $Client['ClientID'] . ">" . $Client['ClientName'] . "</option>";
										}
									?>
								</select>
						</div>
					</div><br>
					<input type="submit" name="submit" value="Add">
				</form>
			</div>
		</section>
	</div>
<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>