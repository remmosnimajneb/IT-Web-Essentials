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
* Inventory
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "Inventory";
$PageName = "Inventory";

/* Include System Functions */
require_once("../../InitSystem.php");



/* Now if we are adding, editing or deleting - let's run those */
if(isset($_POST)){
	//1. Add New Slug
	if($_POST['Action'] == "AddNew"){

			$Add = "INSERT INTO `Inventory` (`ItemName`, `ItemDescription`, `ItemLocation`, `PurchaseDate`, `WarrantyExpDate`, `ModelNumber`, `SerialNumber`, `ClientID`, `ItemNotes`) VALUES ('" . EscapeSQLEntry($_POST['ItemName']) . "', '" . EscapeSQLEntry($_POST['ItemDescription']) . "', '" . EscapeSQLEntry($_POST['ItemLocation']) . "', '" . EscapeSQLEntry($_POST['PurchaseDate']) . "', '" . EscapeSQLEntry($_POST['WarrantyExpDate']) . "', '" . EscapeSQLEntry($_POST['ModelNumber']) . "', '" . EscapeSQLEntry($_POST['SerialNumber']) . "', '" . EscapeSQLEntry($_POST['ClientID']) . "', '" . EscapeSQLEntry($_POST['Notes']) . "')";
			echo $Add;
			$stm = $DatabaseConnection->prepare($Add);
			$stm->execute();
			$Message = "Added Item!";
		

	} else if($_POST['Action'] == "Edit"){
		//B. Delete?
			if($_POST['DeleteItem'] == "Yes"){
				$Query = "DELETE FROM `Inventory` WHERE `ItemID` = " . EscapeSQLEntry($_POST['ItemID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Item Removed!";
			} else {
		//C. Update Item
				$Query = "UPDATE `Inventory` SET `ItemName` = '" . EscapeSQLEntry($_POST['ItemName']) . "', `ItemDescription` = '" . EscapeSQLEntry($_POST['ItemDescription']) . "', `ItemLocation` = '" . EscapeSQLEntry($_POST['ItemLocation']) . "', `PurchaseDate` = '" . EscapeSQLEntry($_POST['PurchaseDate']) . "', `WarrantyExpDate` = '" . EscapeSQLEntry($_POST['WarrantyExpDate']) . "', `ModelNumber` = '" . EscapeSQLEntry($_POST['ModelNumber']) . "', `SerialNumber` = '" . EscapeSQLEntry($_POST['SerialNumber']) . "', `ClientID` = '" . EscapeSQLEntry($_POST['ClientID']) . "' , `ItemNotes` = '" . EscapeSQLEntry($_POST['Notes']) . "' WHERE `ItemID` = " . EscapeSQLEntry($_POST['ItemID']);

				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Updated Item!";
			}
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
				<h2>Inventory</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<?php
					/* Allow Editing and Deleteing of Item */
					if(isset($_GET['ItemID'])){
						$Item = "SELECT 
								*
				  				FROM `Inventory` WHERE `ItemID` = '" . EscapeSQLEntry($_GET['ItemID']) . "'";
						$stm = $DatabaseConnection->prepare($Item);
						$stm->execute();
						$Item = $stm->fetchAll()[0];
				?>
					<h3>Edit Item</h3>
					<form action="index.php" method="POST">
						<input type="hidden" name="Action" value="Edit">
						<input type="hidden" name="ItemID" value="<?php echo $_GET['ItemID']; ?>">
						<div class="row">
							<div class="column">
								Name: <input type="text" name="ItemName" required="required" value="<?php echo $Item['ItemName']; ?>">
							</div>
							<div class="column">
								Description: <input type="text" name="ItemDescription" value="<?php echo $Item['ItemDescription']; ?>">
							</div>
							<div class="column">
								Location: <input type="text" name="ItemLocation" value="<?php echo $Item['ItemLocation']; ?>">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Purchase Date: <input type="date" name="PurchaseDate" value="<?php echo $Item['PurchaseDate']; ?>">
							</div>
							<div class="column">
								Warranty Expiration Date: <input type="date" name="WarrantyExpDate" value="<?php echo $Item['WarrantyExpDate']; ?>">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Model Number: <input type="text" name="ModelNumber" value="<?php echo $Item['ModelNumber']; ?>">
							</div>
							<div class="column">
								Serial Number: <input type="text" name="SerialNumber" value="<?php echo $Item['SerialNumber']; ?>">
							</div>
						</div>
							<br>
						<div class="row">
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
												echo "<option value=" . $Client['ClientID'];
													if($Item['ClientID'] == $Client['ClientID']) echo " seleted='selected'";
												echo ">" . $Client['ClientName'] . "</option>";
											}
										?>
									</select>
							</div>
							<div class="column">
								Notes: <textarea name="Notes"><?php echo $Item['ItemNotes']; ?></textarea>
							</div>
						</div>
							<br>
						<input type='checkbox' name='DeleteItem' value='Yes' id='DeleteItem'><label for='DeleteItem'>Delete Item?</label>
							<br>
						<input type="submit" name="submit" value="Edit">
					</form>
				<?php
					}
				?>
				<table class="alt">
				  <thead>
				    <tr>
				      <th scope="col" style="width:35px">Name</th>
				      <th scope="col" style="width:35px">Description</th>
				      <th scope="col" style="width:35px">Location</th>
				      <th scope="col" style="width:35px">Model</th>
				      <th scope="col" style="width:35px">Client</th>
				      <th scope="col" style="width:85px">Notes</th>
				      <th scope="col" style="width:35px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all Items
				  		$Query = "SELECT 
				  				I.`ItemID`,
				  				I.`ItemName`,
				  				I.`ItemDescription`,
				  				I.`ItemLocation`,
				  				I.`ModelNumber`,
				  				I.`ItemNotes`,
				  				C.`ClientSlug`
				  				FROM `Inventory` AS I INNER JOIN `Client` AS C ON C.`ClientID` = I.`ClientID`
				  				ORDER BY `ItemID` ASC";
				  		$stm = $DatabaseConnection->prepare($Query);
						$stm->execute();
						$Items = $stm->fetchAll();
						foreach ($Items as $Item) {
							echo "<tr>";
								echo "<td data-label='Name'>" . $Item['ItemName'] . "</td>";
								echo "<td data-label='Description'>" . $Item['ItemDescription'] . "</td>";
								echo "<td data-label='Location'>" . $Item['ItemLocation'] . "</td>";
								echo "<td data-label='Model'>" . $Item['ModelNumber'] . "</td>";
								echo "<td data-label='Client'>" . ucfirst($Item['ClientSlug']) . "</td>";
								echo "<td data-label='Client'>" . $Item['ItemNotes'] . "</td>";
								echo "<td data-label='Edit'><a href='index.php?ItemID=" . $Item['ItemID'] . "'>Edit</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
				<h3>Add Item</h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="AddNew">
					<div class="row">
						<div class="column">
							Name: <input type="text" name="ItemName" required="required">
						</div>
						<div class="column">
							Description: <input type="text" name="ItemDescription">
						</div>
						<div class="column">
							Location: <input type="text" name="ItemLocation">
						</div>
					</div>
						<br>
					<div class="row">
						<div class="column">
							Purchase Date: <input type="date" name="PurchaseDate">
						</div>
						<div class="column">
							Warranty Expiration Date: <input type="date" name="WarrantyExpDate">
						</div>
					</div>
						<br>
					<div class="row">
						<div class="column">
							Model Number: <input type="text" name="ModelNumber">
						</div>
						<div class="column">
							Serial Number: <input type="text" name="SerialNumber">
						</div>
					</div>
						<br>
					<div class="row">
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
						<div class="column">
							Notes: <textarea name="Notes"></textarea>
						</div>
					</div>
						<br>
					<input type="submit" name="submit" value="Add">
				</form>
			</div>
		</section>
	</div>

<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>