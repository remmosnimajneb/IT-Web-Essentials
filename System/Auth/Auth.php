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
* Authentication Page
* Check if user exists and password hash is correct
* If good, Set proper SESIONS for user
*/

//Include functions
require_once("../../SystemFunctions.php");

//Hash the Password from the form and SQL Safe the Username
$Email = EscapeSQLEntry($_POST['Email']);
$Password = EscapeSQLEntry($_POST['Password']);

//Check Database if a user exists
$Login = "SELECT * FROM `SysUsers` WHERE `Email` = '" . $Email . "' LIMIT 1";
$stm = $DatabaseConnection->prepare($Login);
$stm->execute();
$records = $stm->fetchAll();
$RowCount = $stm->rowCount();

//Now check if a user exists and that the password matches
if($RowCount > 0 && password_verify($Password, $records[0]["Password"])){
	
	// Set the Sessions
	$_SESSION['IsLoggedIn'] = true;
	$_SESSION['Email'] = $records[0]["Email"];
	$_SESSION['Name'] = $records[0]["Name"];
	$_SESSION['ConsultantSlug'] = $records[0]["ConsultantSlug"];

	//Redirect to index.php
	header('Location: /System/Switchboard.php');
} else {
	// Kill the Sessions and back to Login

	// Set the Sessions
	$_SESSION['IsLoggedIn'] = false;
	$_SESSION['Email'] = null;
	$_SESSION['Name'] = null;
	$_SESSION['ConsultantSlug'] = null;
	header('Location: /System/Auth/Login.php?Message=Error, Username or Password incorrect!');
}
?>