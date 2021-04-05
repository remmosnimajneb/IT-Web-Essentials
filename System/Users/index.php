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
* System Users
*/

/* Page Variables */
$PageSecurityLevel = 2;
$AppName = "SystemConfiguration";
$PageName = "Users";

/* Include System Functions */
require_once("../../InitSystem.php");

/* Now if we are adding, editing or deleting - let's run those */
if(isset($_POST)){
	//1. Add New Slug
	if($_POST['Action'] == "AddNew"){
		//A. Check email or Slug not used yet
		$Check = "SELECT `ConsultantSlug` FROM `User` WHERE `ConsultantSlug` = '" . EscapeSQLEntry($_POST['Slug']) . "' OR `Email` = '" . EscapeSQLEntry($_POST['Email']) . "'";
		$stm = $DatabaseConnection->prepare($Check);
		$stm->execute();
		$RowCount = $stm->rowCount();

		if($RowCount <= 0 && ($_POST['Password'] == $_POST['PasswordConfirm'])){

			$AvailableSystems = json_decode(GetSysConfig("ActivatedSystems"));
			$SystemPermissions;
			foreach ($AvailableSystems as $SysKey => $SysValue) {
				$SystemPermissions->$SysKey = "0";
				if(in_array($SysKey, $_POST['ActivatedSystems'])){
					$SystemPermissions->$SysKey = "1";
				}
				
			}			

			$Add = "INSERT INTO `User` (Name, Email, ConsultantSlug, StreetName, City, State, ZIP, Phone, Fax, Password, SecurityLevel, SysPermissions, Notes) VALUES ('" . EscapeSQLEntry($_POST['Name']) . "', '" . EscapeSQLEntry($_POST['Email']) . "', '" . EscapeSQLEntry($_POST['Slug']) . "', '" . EscapeSQLEntry($_POST['StreetName']) . "', '" . EscapeSQLEntry($_POST['City']) . "', '" . EscapeSQLEntry($_POST['State']) . "', '" . EscapeSQLEntry($_POST['ZIP']) . "', '" . EscapeSQLEntry($_POST['Phone']) . "', '" . EscapeSQLEntry($_POST['Fax']) . "', '" . EscapeSQLEntry(password_hash($_POST['Password'], PASSWORD_DEFAULT)) . "', '" . EscapeSQLEntry($_POST['SecurityLevel']) . "', '" . json_encode($SystemPermissions) . "', '" . EscapeSQLEntry($_POST['Notes']) . "')";
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

				if($_POST['UserID'] == 1){
					$Message = "Error, this user is required for System use, you cannot delete this account!";
				} else {
					$Query = "DELETE FROM `User` WHERE `UserID` = " . EscapeSQLEntry($_POST['UserID']);
					$stm = $DatabaseConnection->prepare($Query);
					$stm->execute();
					$Message = "User Removed!";
				}

			} else {
		//C. Update User

				/* Check Email given is not used by another User already */
			$CheckEmail = "SELECT `UserID` FROM `User` WHERE `Email` = '" . EscapeSQLEntry($_POST['Email']) . "'";
			$stm = $DatabaseConnection->prepare($CheckEmail);
			$stm->execute();
			$RowCount = $stm->rowCount();
			$Records = $stm->fetchAll();

			if($RowCount == 0 || $_POST['UserID'] == $Records[0]["UserID"]) {
			
				$AvailableSystems = json_decode(GetSysConfig("ActivatedSystems"));
				$SystemPermissions;
				foreach ($AvailableSystems as $SysKey => $SysValue) {
					$SystemPermissions->$SysKey = "0";
					if(in_array($SysKey, $_POST['ActivatedSystems'])){
						$SystemPermissions->$SysKey = "1";
					}
					
				}

				$Query = "UPDATE `User` SET `Name` = '" . EscapeSQLEntry($_POST['Name']) . "', `Email` = '" . EscapeSQLEntry($_POST['Email']) . "', `StreetName` = '" . EscapeSQLEntry($_POST['StreetName']) . "', `City` = '" . EscapeSQLEntry($_POST['City']) . "', `State` = '" . EscapeSQLEntry($_POST['State']) . "', `ZIP` = '" . EscapeSQLEntry($_POST['ZIP']) . "', `Phone` = '" . EscapeSQLEntry($_POST['Phone']) . "', `Fax` = '" . EscapeSQLEntry($_POST['Fax']) . "', `SecurityLevel` = '" . EscapeSQLEntry($_POST['SecurityLevel']) . "', `SysPermissions` = '" . json_encode($SystemPermissions) . "', `Notes` = '" . EscapeSQLEntry($_POST['Notes']) . "' WHERE `UserID` = " . EscapeSQLEntry($_POST['UserID']);
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Message = "Updated User!";

			} 
		} 
	} else if($_POST['Action'] == "ResetPassword"){
		//D. Reset Password
		if($_POST['Password'] == $_POST['PasswordConfirm']){
			$Query = "UPDATE `User` SET `Password` = '" . EscapeSQLEntry(password_hash($_POST['Password'], PASSWORD_DEFAULT)) . "' WHERE `UserID` = " . EscapeSQLEntry($_POST['UserID']);
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Password Reset!";
		} else {
			$Message = "Error, please check Passwords match!";
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
				<h2>System Users</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<?php
					/* Allow Editing and Deleteing of User */
					if(isset($_GET['UserID']) && $_GET["Action"] == "Edit"){
						$UserQuery = "SELECT
								UserID, 
								Name, 
								Email, 
								ConsultantSlug, 
								StreetName, 
								City, 
								State, 
								ZIP,
								Phone, 
								Fax, 
								SecurityLevel, 
								SysPermissions, 
								Notes
				  				FROM `User` WHERE `UserID` = '" . EscapeSQLEntry($_GET['UserID']) . "'";
						$stm = $DatabaseConnection->prepare($UserQuery);
						$stm->execute();
						$User = $stm->fetchAll()[0];
				?>
					<h3>Edit User</h3>
					<form action="index.php" method="POST">
						<input type="hidden" name="Action" value="EditUser">
						<input type="hidden" name="UserID" value="<?php echo $User['UserID']; ?>">
						<br><h3>System Info</h3>
						<div class="row">
							<div class="column">
								Name: <input type="text" name="Name" required="required" value="<?php echo $User['Name']; ?>">
							</div>
							<div class="column">
								Email: <input type="email" name="Email" required="required" value="<?php echo $User['Email']; ?>">
							</div>
							<div class="column">
								Slug: (Cannot be changed)<input type="text" name="Slug" required="required" value="<?php echo $User['ConsultantSlug']; ?>" disabled="disabled">
							</div>
						</div>
						<br><h3>Personal Info</h3>
						<div class="row">
							<div class="column">
								Street Address: <input type="text" name="StreetName" value="<?php echo $User['StreetName']; ?>">
							</div>
							<div class="column">
								City: <input type="text" name="City" value="<?php echo $User['City']; ?>">
							</div>
							<div class="column">
								State: <input type="text" name="State" value="<?php echo $User['State']; ?>">
							</div>
							<div class="column">
								ZIP: <input type="text" name="ZIP" value="<?php echo $User['ZIP']; ?>">
							</div>
						</div>
						<div class="row">
							<div class="column">
								Phone: <input type="text" name="Phone" value="<?php echo $User['Phone']; ?>">
							</div>
							<div class="column">
								Fax: <input type="text" name="Fax" value="<?php echo $User['Fax']; ?>">
							</div>
						</div>
						<br><h3>Security/Permissions</h3>
						<div class="row">
							<div class="column">
								Security Level:
								<select name="SecurityLevel" required="required">
									<option value="0" <?php if($User['SecurityLevel'] == "0") echo " selected='selected'"; ?>>No Site Role</option>
									<option value="1" <?php if($User['SecurityLevel'] == "1") echo " selected='selected'"; ?>>Standard User</option>
									<option value="2" <?php if($User['SecurityLevel'] == "2") echo " selected='selected'"; ?>>System Administrator</option>
								</select>
							</div>
							<div class="column">
								System Permissions:
								<?php 
									$ActivatedSystems = json_decode(GetSysConfig("ActivatedSystems"), true);
									$AllowedSystems = json_decode($User["SysPermissions"], true);
									foreach ($ActivatedSystems as $SysKey => $SysValue) {
										echo "<br><input type='checkbox' id='Edit_" . $SysKey . "' name='ActivatedSystems[]' value='" . $SysKey . "'";
											if($AllowedSystems[$SysKey] == "1"){
												echo " checked='checked'";
											}
										echo "><label for='Edit_" . $SysKey. "'>" . preg_replace('/(?<!\ )[A-Z]/', ' $0', $SysKey) . "</label>";
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="column">
								Notes: <textarea name="Notes"><?php echo $User['Notes']; ?></textarea><br>
							</div>
						</div>
						<?php
							$DisableDelete = "";
							if($User['UserID'] == 1){
								$DisableDelete = "disabled='disabled'";
								?><i>This User account is reserved for System use and cannot be removed. You can rename or edit other details of this user, but you cannot delete it.</i><br><?php
							}
						?>
						<input type='checkbox' name='DeleteUser' value='Yes' id='Delete' <?php echo $DisableDelete; ?>><label for='Delete'>Delete User? </label>
							<br><br>
						<input type="submit" name="submit" value="Edit">
					</form>
				<?php
					} else if(isset($_GET['UserID']) && $_GET["Action"] == "ResetPW"){
				?>
					<h3>Reset Password</h3>
					<form action="index.php" method="POST">
						<input type="hidden" name="Action" value="ResetPassword">
						<input type="hidden" name="UserID" value="<?php echo $_GET['UserID']; ?>">
						Password: <input type="password" name="Password"><br>
						Confirm Password: <input type="password" name="PasswordConfirm"><br>
						<input type="submit" name="submit" value="Reset">
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
				      <th scope="col" style="width:35px">Security Level</th>
				      <th scope="col" style="width:35px">Edit</th>
				      <th scope="col" style="width:35px">Reset Password</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  		//Get all ShortLinks
				  		$Query = "SELECT 
				  				`Name`,
				  				`ConsultantSlug`,
				  				`Email`,
				  				`UserID`,
				  				CASE 
				  					WHEN `SecurityLevel` = 0 THEN 'No Site Role'
				  					WHEN `SecurityLevel` = 1 THEN 'Standard User'
				  					WHEN `SecurityLevel` = 2 THEN 'System Administrator'
				  				END AS `SecurityLevel`
				  				FROM `User` ORDER BY `UserID` ASC";
				  		$stm = $DatabaseConnection->prepare($Query);
						$stm->execute();
						$Users = $stm->fetchAll();
						foreach ($Users as $User) {
							echo "<tr>";
								echo "<td data-label='Name'>" . ucfirst($User['Name']) . "</td>";
								echo "<td data-label='Slug'>" . ucfirst($User['ConsultantSlug']) . "</td>";
								echo "<td data-label='Email'>" . $User['Email'] . "</td>";
								echo "<td data-label='Security Level'>" . $User['SecurityLevel'] . "</td>";
								echo "<td data-label='Edit'><a href='index.php?UserID=" . $User['UserID'] . "&Action=Edit'>Edit</a></td>";
								echo "<td data-label='Reset Password'><a href='index.php?UserID=" . $User['UserID'] . "&Action=ResetPW'>Reset</a></td>";
							echo "</tr>";
						}
				  	?>
				  </tbody>
				</table>
					<hr>
				<h3>Add User</h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="AddNew">
					<br><h3>System Info</h3>
					<div class="row">
						<div class="column">
							Name: <input type="text" name="Name" required="required">
						</div>
						<div class="column">
							Email: <input type="email" name="Email" required="required">
						</div>
						<div class="column">
							Slug: <input type="text" name="Slug" required="required">
						</div>
					</div>
					<br><h3>Personal Info</h3>
					<div class="row">
						<div class="column">
							Street Address: <input type="text" name="StreetName">
						</div>
						<div class="column">
							City: <input type="text" name="City">
						</div>
						<div class="column">
							State: <input type="text" name="State">
						</div>
						<div class="column">
							ZIP: <input type="text" name="ZIP">
						</div>
					</div>
					<div class="row">
						<div class="column">
							Phone: <input type="text" name="Phone">
						</div>
						<div class="column">
							Fax: <input type="text" name="Fax">
						</div>
					</div>
					<br><h3>Security/Permissions</h3>
					<div class="row">
						<div class="column">
							Password: <input type="password" name="Password" required="required">
						</div>
						<div class="column">
							Confirm Password: <input type="password" name="PasswordConfirm" required="required">
						</div>
					</div>
					<div class="row">
						<div class="column">
							Security Level:
							<select name="SecurityLevel" required="required">
								<option value="0">No Site Role</option>
								<option value="1">Standard User</option>
								<option value="2">System Administrator</option>
							</select>
						</div>
						<div class="column">
							System Permissions:
							<?php 
								$ActivatedSystems = json_decode(GetSysConfig("ActivatedSystems"));
								foreach ($ActivatedSystems as $SysKey => $SysValue) {
									echo "<br><input type='checkbox' id='" . $SysKey . "' name='ActivatedSystems[]' value='" . $SysKey . "'><label for='" . $SysKey. "'>" . preg_replace('/(?<!\ )[A-Z]/', ' $0', $SysKey) . "</label>";
									
								}
							?>
						</div>
					</div>
					<div class="row">
						<div class="column">
							Notes: <textarea name="Notes"></textarea><br>
						</div>
					</div>
					<input type="submit" name="submit" value="Add">
				</form>
			</div>
		</section>
	</div>
<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>