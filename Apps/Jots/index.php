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
* Index for Short Notes
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "Jots";
$PageName = "Jots";

/* Include System Functions */
require_once("../../InitSystem.php");

//Check if checkbox for delete is clicked
	$DeleteOnOpen = 0;
	if(!empty($_POST['JotDeleteOnOpen'])){ $DeleteOnOpen = 1; }

/* Now if we are adding, editing or deleting - let's run those */
if(isset($_POST)){
	//1. Add New Slug
	if($_POST['Action'] == "AddNew"){
		//A. Check it's not used yet
		$Check = "SELECT `JotID` FROM `Jot` WHERE `JotSlug` = '" . EscapeSQLEntry($_POST['JotSlug']) . "'";
		$stm = $DatabaseConnection->prepare($Check);
		$stm->execute();
		$records = $stm->fetchAll();
		$RowCount = $stm->rowCount();

		if($RowCount == 0) {
			$Add = "INSERT INTO `Jot` (JotSlug, JotPassword, JotDeleteOnOpen, JotType, JotContent, JotHits) VALUES ('" . EscapeSQLEntry($_POST['JotSlug']) . "', '" . EscapeSQLEntry($_POST['JotPassword']) . "', '" . $DeleteOnOpen . "', '" . EscapeSQLEntry($_POST['JotType']) . "', '" . EscapeSQLEntry($_POST['JotContent']) . "', 0)";
			$stm = $DatabaseConnection->prepare($Add);
			$stm->execute();
			$Message = "Added Note!";
		} else {
			$Message = "Error! Slug already in use, please edit or delete exisiting Note!";
		}

	} else if($_POST['Action'] == "EditJot"){
		//B. Delete?
			if($_POST['DeleteJotEdit'] == "Yes"){
				$Query = "DELETE FROM `Jot` WHERE `JotID` = " . EscapeSQLEntry($_POST['JotID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Jot Removed!";
			} else {
		//C. Update Jot
				$Query = "UPDATE `Jot` SET `JotPassword` = '" . EscapeSQLEntry($_POST['JotPassword']) . "', `JotDeleteOnOpen` = '" . $DeleteOnOpen . "', `JotType` = '" . EscapeSQLEntry($_POST['JotTypeEdit']) . "', `JotContent` = '" . EscapeSQLEntry($_POST['JotContent']) . "' WHERE `JotID` = " . EscapeSQLEntry($_POST['JotID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Updated Jot!";
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
				<h2>Jots</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<?php
					/* Allow Editing and Deleteing of Jot */
					if(isset($_GET['JotID'])){
						$Jot = "SELECT 
								`JotSlug`,
				  				`JotContent`,
				  				`JotPassword`,
				  				`JotHits`,
				  				`JotID`, 
				  				`JotType`,
				  				`JotDeleteOnOpen`
				  				FROM `Jot` WHERE `JotID` = '" . EscapeSQLEntry($_GET['JotID']) . "'";
						$stm = $DatabaseConnection->prepare($Jot);
						$stm->execute();
						$records = $stm->fetchAll();
				?>
					<h3>Edit Jot</h3>
					<form action="index.php" method="POST">
						<input type="hidden" name="Action" value="EditJot">
						<input type="hidden" name="JotID" value="<?php echo $records[0]['JotID']; ?>">
						Jot Slug <i>(Create new Jot to edit Slug)</i>: <input type="text" name="JotSlug" required="required" value="<?php echo strtoupper($records[0]['JotSlug']); ?>" disabled="disabled">
							<br>
						Jot Type: <br>
							<input type="radio" id="JotTypeLinkEdit" name="JotTypeEdit" value="link" <?php if($records[0]['JotType'] == 'link') echo 'checked="checked"'; ?> required="required"><label for="JotTypeLinkEdit">Link</label><br>
							<input type="radio" id="JotTypeNoteEdit" name="JotTypeEdit" value="note" <?php if($records[0]['JotType'] == 'note') echo 'checked="checked"'; ?> required="required"><label for="JotTypeNoteEdit">Note </label><br>
						Jot Content: <textarea name="JotContent" required="required"><?php echo $records[0]['JotContent']; ?></textarea>
							<br>
						Password: <i>For no password, leave empty</i><input type="text" name="JotPassword" value="<?php echo $records[0]['JotPassword']; ?>">
							<br><br>

							<input type='checkbox' name='JotDeleteOnOpenEdit' value='Yes' id='DeleteJotOnOpen' <?php if($record[0]['JotDeleteOnOpen'] == 1) echo 'checked="checked"'; ?>>
						<label for="DeleteJotOnOpen">Delete Jot On Open?</label><br>
				
							<input type='checkbox' name='DeleteJotEdit' value='Yes' id='DeleteJot'>
						<label for='DeleteJot'>Delete Jot?</label>
							<br><br>
						<input type="submit" name="submit" value="Save">
					</form>
				<?php
					}
				?>
					<hr>
				<table>
				  <caption>Jots</caption>
				  <thead>
				    <tr>
				      <th scope="col" style="width:30px">Jot Slug</th>
				      <th scope="col" style="width:35px">Jot Type</th>
				      <th scope="col" style="width:85px">Jot Content</th>
				      <th scope="col" style="width:35px">Jot Delete on Open</th>
				      <th scope="col" style="width:35px">Password</th>
				      <th scope="col" style="width:35px">Hits</th>
				      <th scope="col" style="width:35px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all Jots
				  		$JotsQuery = "SELECT 
				  				`JotID`,
				  				`JotSlug`,
				  				`JotContent`,
				  					IF(`JotPassword` != '', `JotPassword`, 'No Password') AS `JotPassword`,
				  				`JotHits`,
				  				`JotType`,
				  					IF(`JotDeleteOnOpen` = 1, 'Yes', 'No') AS `JotDeleteOnOpen`
				  				FROM `Jot` ORDER BY `JotID` ASC";
				  		$stm = $DatabaseConnection->prepare($JotsQuery);
						$stm->execute();
						$Jots = $stm->fetchAll();
						foreach ($Jots as $Jot) {
							echo "<tr>";
								echo "<td data-label='Jot Slug'><a href='" . $SystemPublicURL . "/" . $Jot['JotSlug'] . "'>" . strtoupper($Jot['JotSlug']) . "</a></td>";
								echo "<td data-label='Jot Type'>" . ucfirst($Jot['JotType']) . "</td>";
									if($Jot['JotType'] == "link"){
										echo "<td data-label='Jot Content'><a href='" . $Jot['JotContent'] . "'>" . $Jot['JotContent'] . "</a></td>";
									} else {
										echo "<td data-label='Jot Content'><textarea style='text-align: center;'>" . $Jot['JotContent'] . "</textarea></td>";
									}
								echo "<td data-label='Jot Delete on Open'>" . $Jot['JotDeleteOnOpen'] . "</td>";
								echo "<td data-label='Jot Password'>" . $Jot['JotPassword'] . "</td>";
								echo "<td data-label='Hits'>" . $Jot['JotHits'] . "</td>";
								echo "<td data-label='Edit'><a href='index.php?JotID=" . $Jot['JotID'] . "'>Edit</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
					<hr>
				<h3>Add New Jot</h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="AddNew">
					Jot Slug: <input type="text" name="JotSlug" required="required"><br>
					Jot: <textarea name="JotContent" required="required"></textarea><br>
					Jot Type: <br>
							<input type="radio" id="link" name="JotType" value="link" required="required">
								<label for = "link">Link</label><br>
							<input type="radio" id="note" name="JotType" value="note" required="required">
								<label for="note">Note </label><br><br>
					<input type="checkbox" id="JotDeleteOnOpen" name="JotDeleteOnOpen" value="JotDeleteOnOpen">
						<label for="JotDeleteOnOpen">Delete On Open</label><br>
					Password: <i>For no password, leave empty</i><input type="text" name="JotPassword"><br>
					<input type="submit" name="submit" value="Add">
				</form>
			</div>
		</section>
	</div>

<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>