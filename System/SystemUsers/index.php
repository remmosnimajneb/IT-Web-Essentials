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
/*
* System Users
*/

/* Include System Functions */
require_once("../../SystemFunctions.php");

/* Init System */
Init(false);

/* Set System */
$CurrentSystem = "SystemUsers";

/* Now if we are adding, editing or deleting - let's run those */
if(isset($_POST)){
	//1. Add New Slug
	if($_POST['Action'] == "AddNew"){
		//A. Check it's not used yet
		$Check1 = "SELECT `ConsultantSlug` FROM `SysUsers` WHERE `ConsultantSlug` = '" . EscapeSQLEntry($_POST['Slug']) . "'";
		$stm = $DatabaseConnection->prepare($Check1);
		$stm->execute();
		$RowCount1 = $stm->rowCount();

		$Check2 = "SELECT `Email` FROM `SysUsers` WHERE `Email` = '" . EscapeSQLEntry($_POST['Email']) . "'";
		$stm = $DatabaseConnection->prepare($Check2);
		$stm->execute();
		$RowCount2 = $stm->rowCount();
		if($RowCount1 == 0 && $RowCount2 == 0 && ($_POST['Password'] == $_POST['PasswordConfirm'])){
			$Add = "INSERT INTO `SysUsers` (Name, Email, ConsultantSlug, Password) VALUES ('" . EscapeSQLEntry($_POST['Name']) . "', '" . EscapeSQLEntry($_POST['Email']) . "', '" . EscapeSQLEntry($_POST['Slug']) . "', '" . EscapeSQLEntry(password_hash($_POST['Password'], PASSWORD_DEFAULT)) . "')";
			$stm = $DatabaseConnection->prepare($Add);
			$stm->execute();
			$Message = "User Added!";
		} else {
			if($_POST['Password'] != $_POST['PasswordConfirm']){
				$Message = "Error! Password doesn't match!";
			} else {
				$Message = "Error! Slug or Email already in use, please edit or delete exisiting User!";
			}
		}
	} else if($_POST['Action'] == "EditUser"){
		//B. Delete?
			if($_POST['DeleteUser'] == "Yes"){
				$Query = "DELETE FROM `SysUsers` WHERE `UserID` = " . EscapeSQLEntry($_POST['UserID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "User Removed!";
			} else {
		//C. Update Link
			$Check1 = "SELECT `UserID` FROM `SysUsers` WHERE `ConsultantSlug` = '" . EscapeSQLEntry($_POST['Slug']) . "'";
			$stm = $DatabaseConnection->prepare($Check1);
			$stm->execute();
			$RowCount1 = $stm->rowCount();
			$Records1 = $stm->fetchAll();

			$Check2 = "SELECT `UserID` FROM `SysUsers` WHERE `Email` = '" . EscapeSQLEntry($_POST['Email']) . "'";
			$stm = $DatabaseConnection->prepare($Check2);
			$stm->execute();
			$RowCount2 = $stm->rowCount();
			$Records2 = $stm->fetchAll();

		if(($RowCount1 == 0 || $_POST['UserID'] == $Records1[0]["UserID"]) && ($RowCount2 == 0 || $_POST['UserID'] == $Records2[0]["UserID"])) {
				
				if($_POST['Password'] != "" && $_POST['Password'] == $_POST['PasswordConfirm']){
					$Password = ", `Password` = '" . password_hash(EscapeSQLEntry($_POST['Password'], PASSWORD_DEFAULT)) . "'";
				}

				$Query = "UPDATE `SysUsers` SET `ConsultantSlug` = '" . EscapeSQLEntry($_POST['ConsultantSlug']) . "', `Name` = '" . EscapeSQLEntry($_POST['Name']) . "', `Email` = '" . EscapeSQLEntry($_POST['Email']) . "'" . $Password . " WHERE `UserID` = " . EscapeSQLEntry($_POST['UserID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Updated User!";

			}
		}
	}
}
$PageName = "System Users";
require_once('../../SystemAssets/Views/BillingPortalHeader.php'); 
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>System Users</h2>
				<h3>Welcome back <?php echo $_SESSION['Name']; ?></h3>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<?php
					/* Allow Editing and Deleteing of Note */
					if(isset($_GET['UserID'])){
						$User = "SELECT 
								`Name`,
				  				`ConsultantSlug`,
				  				`Email`,
				  				`UserID`
				  				FROM `SysUsers` WHERE `UserID` = '" . EscapeSQLEntry($_GET['UserID']) . "'";
						$stm = $DatabaseConnection->prepare($User);
						$stm->execute();
						$records = $stm->fetchAll();
				?>
					<h3>Edit User</h3>
					<form action="index.php" method="POST">
						<input type="hidden" name="Action" value="EditUser">
						<input type="hidden" name="UserID" value="<?php echo $records[0]['UserID']; ?>">
						Name: <input type="text" name="Name" required="required" value="<?php echo $records[0]['Name']; ?>">
							<br>
						Consultant Slug: <i>(User Slug cannot be Changed)</i> <input type="text" name="ConsultantSlug" required="required" value="<?php echo $records[0]['ConsultantSlug']; ?>" disabled="disabled">
							<br>
						Password: <input type="password" name="Password"><br>
						Confirm Password: <input type="password" name="PasswordConfirm"><br>
							<br>
						<input type='checkbox' name='DeleteUser' value='Yes' id='Delete'><label for='Delete'>Delete User? </label>
							<br><br>
						<input type="submit" name="submit" value="Save">
					</form>
				<?php
					}
				?>
					<hr>
				<table>
				  <caption>System Users</caption>
				  <thead>
				    <tr>
				      <th scope="col" style="width:30px">Name</th>
				      <th scope="col" style="width:35px">Slug</th>
				      <th scope="col" style="width:35px">Email</th>
				      <th scope="col" style="width:35px">Edit</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all ShortLinks
				  		$sql = "SELECT 
				  				`Name`,
				  				`ConsultantSlug`,
				  				`Email`,
				  				`UserID`
				  				FROM `SysUsers` ORDER BY `UserID` ASC";
				  		$stm = $DatabaseConnection->prepare($sql);
						$stm->execute();
						$records = $stm->fetchAll();
						foreach ($records as $row) {
							echo "<tr>";
								echo "<td data-label='Name'>" . ucfirst($row['Name']) . "</td>";
								echo "<td data-label='Slug'>" . ucfirst($row['ConsultantSlug']) . "</td>";
								echo "<td data-label='Email'>" . $row['Email'] . "</td>";
								echo "<td data-label='Edit'><a href='index.php?UserID=" . $row['UserID'] . "'>Edit</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
					<hr>
				<h3>Add User</h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="AddNew">
					Name: <input type="text" name="Name" required="required"><br>
					Email: <input type="text" name="Email" required="required"><br>
					Slug: <input type="text" name="Slug" required="required"><br>
					Password: <input type="password" name="Password" required="required"><br>
					Confirm Password: <input type="password" name="PasswordConfirm" required="required"><br>
					<input type="submit" name="submit" value="Add">
				</form>
			</div>
		</section>
	</div>
<?php require_once('../../SystemAssets/Views/BillingPortalFooter.php');  ?>