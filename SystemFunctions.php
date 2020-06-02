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
* Main System Functions
* Store functions which apply to all systems (Including MYSQL Connections and other)
*/

/* Include User Configuration */
require_once('UserConfig.php');

/* MySQL Connection Info */
$DatabaseConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", "" . DB_USER . "", "" . DB_PASSWORD . "");

/*
* Safe input to MySQL
*/
function EscapeSQLEntry($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = addslashes($data);
   return $data;
}

//Start Sessions for Logins
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
* Check User Is Logged in before each page load
*/
function Authenticate(){
	if(isset($_SESSION['IsLoggedIn']) && $_SESSION['IsLoggedIn'] && $_SESSION['Name'] != null && $_SESSION['Email'] != null && $_SESSION['ConsultantSlug'] != null){
		return true;
	} else {
		return false;
	}
}

/* 
* Init System
* Initiate System and check Login and other System needs
*/
function Init($DoNotRedirect){

   $Error = false;
   $RedirectTo = "/System/Switchboard.php";

   /* 1. Check Authentication */
   if(!Authenticate()){
      $Error = true;
      $RedirectTo = "/System/Auth/Login.php?Message=Please Login to Procceed!";
   }


   if($Error && !$DoNotRedirect){
      header('Location: ' . $RedirectTo);
      die();
      exit();
   }

}

?>