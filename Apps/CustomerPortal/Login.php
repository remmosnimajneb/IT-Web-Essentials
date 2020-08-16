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
* Customer Portal
* Login & Auth Page
*/


/* Page Variables */
$PageSecurityLevel = 0;
$AppletName = "PortalAccess";
$AppName = "CustomerPortal";
$PageName = "Login - Customer Portal";

/* Include System Functions */
require_once("../../InitSystem.php");

$Message = "";

/* If a Login attempt was made */
if(isset($_POST)){

	//Check Database if a user exists
	$Login = "SELECT * FROM `ClientUser` WHERE `Email` = '" . EscapeSQLEntry($_POST['Email']) . "' LIMIT 1";
	$stm = $DatabaseConnection->prepare($Login);
	$stm->execute();
	$ClientUser = $stm->fetchAll();

	//Now check if a User Exists, Password matches and the User has Portal Access Enabled
	if($stm->rowCount() > 0 && password_verify($_POST['Password'], $ClientUser[0]["Password"]) && $ClientUser[0]["PortalAccessAllowed"] == 1){

		// Set the Sessions
		$_SESSION['ClientPortal_IsLoggedIn'] = true;
		$_SESSION['ClientPortal_ClientUserID'] = $ClientUser[0]["ClientUserID"];
		$_SESSION['ClientPortal_Email'] = $ClientUser[0]["Email"];
		$_SESSION['ClientPortal_Name'] = $ClientUser[0]["Name"];

			header('Location: Dashboard.php');

	} else {
		// Kill the Sessions and back to Login

		// Set the Sessions
		$_SESSION['ClientPortal_IsLoggedIn'] = false;
		$_SESSION['ClientPortal_ClientUserID'] = null;
		$_SESSION['ClientPortal_Email'] = null;
		$_SESSION['ClientPortal_Name'] = null;
		
		$Message = "Uh oh, that didn't work! Check your Email and/or Password!";
	}

}

// Include Header
require_once('Assets/Views/Header.php');

?>
<!-- Main -->
	<section id="main" class="wrapper alt">
		<div class="inner">
			<header class="major special">
				<p>Customer Portal</p>
				<h1>Please Login</h1>
			</header>
				<p style="color: orange;"><?php if(isset($Message)) echo $Message; ?></p>
			<form action="Login.php" method="POST">
				Email: <input type="Email" name="Email" required="required"><br>
				Password: <input type="Password" name="Password" required="required"><br>
				<input type="submit" name="submit" value="Login">
			</form>
		</div>
	</section>
<?php require_once('Assets/Views/Footer.php'); ?>