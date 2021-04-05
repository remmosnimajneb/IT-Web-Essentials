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
* Startup the System!
*/

/* Note to User: You should not have to edit this file */

	/* 1. Define System Path */
		define( 'SYSPATH', dirname( __FILE__ ) . '/' );

   	/* 2. Check if System has been installed yet */
      if(!file_exists(SYSPATH . '/DatabaseConfig.php')){
        header('Location: /System/Install/Install.php');
        die();
      }

    /* 3. Set Database Config Vars */
		require_once(SYSPATH . '/DatabaseConfig.php');

   	/* 4. Check SQL Connection */
   		try {
			$DatabaseConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", "" . DB_USER . "", "" . DB_PASSWORD . "");
			$DatabaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(Exception $e) {
			header('Location: ' . GetSysConfig("SystemURL") . 'System/Error/CoreError.php?Error=DBConnectError');
			die();
		}

	/* 5. We're a go Scotty! Let's "boot"! */
		/* Include Database Connection */
		$DatabaseConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", "" . DB_USER . "", "" . DB_PASSWORD . "");

		/* (Optional) Email Config */
			include_once(SYSPATH . '/EmailConfig.php');
	
		/* System Functions */
			require_once(SYSPATH . '/SystemFunctions.php');
	
		/* Run Authentication Checks */
			Authenticate($AppName, $PageSecurityLevel, $_SESSION['UserID']);

	/* If Authenticate() didn't throw any errors our actual PHP File will chug along as it should and we're all done here! */