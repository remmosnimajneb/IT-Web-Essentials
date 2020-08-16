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
* Main System Functions
* Store functions which apply to all systems (Including MYSQL Connections and other)
*/

/* Note to User: You should not have to edit this file */

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

/*
* Start Sessions
*/
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 }

/*
* Check User Is Logged in before each page load
*/
function Authenticate($AppName, $CurrentPageSecurityLevel, $UserID){
   
   /* Check User is Logged in */
   if(($CurrentPageSecurityLevel != 0) && (!isset($_SESSION['IsLoggedIn']) || !$_SESSION['IsLoggedIn'] || $_SESSION['Name'] == null || $_SESSION['Email'] == null || $_SESSION['ConsultantSlug'] == null)){
         header('Location: /System/Auth/Login.php?Resource=' . $_SERVER['REQUEST_URI']);
         die();
      } 
   
   /* Check User has the Proper Security Level */
   if(GetUserSecurityLevel($UserID) < $CurrentPageSecurityLevel){
      header('Location: /System/Error/403.php');
      die();
   }

   
   /* Check that Application is enabled and User has permissions */
   if( (json_decode(GetSysConfig("ActivatedSystems"), true)[$AppName] == "0") || (json_decode(GetUserSysPermissions($_SESSION['UserID']), true)[$AppName] == "0") ){
      header('Location: /System/Error/403.php');
      die();
   }
   // Otherwise we're ok for now
}

/*
* Check User Permissions for Customer Portal
*/
function AuthenticateCustomerPortal($AppletName, $PageSecurityLevel, $ClientPortal_ClientUserID){
   
   /* Check User is Logged in */
   if(($PageSecurityLevel != 0) && (!isset($_SESSION['ClientPortal_IsLoggedIn']) || !$_SESSION['ClientPortal_IsLoggedIn'] || $_SESSION['ClientPortal_ClientUserID'] == null || $_SESSION['ClientPortal_Email'] == null || $_SESSION['ClientPortal_Name'] == null)){
         header('Location: /Apps/CustomerPortal/Login.php');
         die();
      } 
   
   /* Check that Application is enabled and User has permissions */
   if( ($PageSecurityLevel != 0) && (GetClientUserPortalPermissions($ClientPortal_ClientUserID, "PortalAccess") == 0 || GetClientUserPortalPermissions($ClientPortal_ClientUserID, $AppletName) == 0) ){
      header('Location: /Apps/CustomerPortal/Login.php');
      die();
   }
   // Otherwise we're ok for now
}

/*
* Get User Security Level based on UserID
*/
function GetUserSecurityLevel($UserID){
   $SecurityLevel = "SELECT `SecurityLevel` FROM `User` WHERE `UserID` = '" . EscapeSQLEntry($UserID) . "' LIMIT 1";
   $stm = $GLOBALS['DatabaseConnection']->prepare($SecurityLevel);
   $stm->execute();
   return $stm->fetchAll()[0]["SecurityLevel"];
}

/*
* Get User Security Level based on UserID
*/
function GetUserSysPermissions($UserID){
   $SysPermissions = "SELECT `SysPermissions` FROM `User` WHERE `UserID` = '" . EscapeSQLEntry($UserID) . "' LIMIT 1";
   $stm = $GLOBALS['DatabaseConnection']->prepare($SysPermissions);
   $stm->execute();
   return $stm->fetchAll()[0]["SysPermissions"];
}

/*
* Get System Configuration Value based on Key
*/
function GetSysConfig($ConfigKey){
   $SysOption = "SELECT `ConfigurationValue` FROM `Configuration` WHERE `ConfigurationKey` = '" . EscapeSQLEntry($ConfigKey) . "' LIMIT 1";
   $stm = $GLOBALS['DatabaseConnection']->prepare($SysOption);
   $stm->execute();
   return $stm->fetchAll()[0]["ConfigurationValue"];
}

/*
* Get User Portal Access Permissions
* @Param ClientUserID - ID of ClientUser
* @Param RequestedAccess - Applet requesting access to - Options: [PortalAccess, ViewInvoices, SubmitTickets]
* @Return int(1) - Allowed to Login or not
*/
function GetClientUserPortalPermissions($ClientUserID, $RequestedAccess){
      $Permission = "PortalAccessAllowed";
   if($RequestedAccess == "ViewInvoices"){
      $Permission = "PortalCanViewInvoices";
   } else if($RequestedAccess == "SubmitTickets"){
      $Permission = "PortalCanSubmitTickets";
   }
   $SysPermissions = "SELECT `" . $Permission . "` FROM `ClientUser` WHERE `ClientUserID` = '" . EscapeSQLEntry($ClientUserID) . "' LIMIT 1";
   $stm = $GLOBALS['DatabaseConnection']->prepare($SysPermissions);
   $stm->execute();
   return $stm->fetchAll()[0][$Permission];
}
?>