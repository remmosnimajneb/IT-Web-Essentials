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
* Configuration Page
* NOTE: V2.0 will turn this (mostly) into a Database Table
*/

	/* 1. Branding Info */
		$SiteTitle = "";
		$BrandingCompanyURL = "";

		//Leave NULL to Not show on Footer
		$BrandingFooterPhoneNumber = NULL;
		$BrandingFooterEmail = NULL;
		$BrandingFooterAddress = NULL;

		$BrandingAboutHeader = "";
		$BrandingAboutSubHeader = "";
		$FooterCopyright = "";

		$BrandingCompanyAddress = "";
		$InvoiceEmailAddress = "";

		$SystemPublicURL = "";
		$SystemAdminURL = "";

		$InvoiceBrandingLogo = "";



	/* 2. MySQL Connection Settings */
		DEFINE( 'DB_HOST', '' );					// HostName
		DEFINE( 'DB_NAME', '' );					// Database Name
		DEFINE( 'DB_USER', '' );					// Database Username
		DEFINE( 'DB_PASSWORD', '' );				// Database Password

	/* 3. Set TimeZone */
		date_default_timezone_set("America/New_York");

	/* 4. Activated Systems, Note: Not used in V1.0 */
		$ActivatedSystems = array('Short Links' => 'ShortLinks', 'Short Notes' => 'ShortNotes', 'Billing Portal - Standard User' => 'BillingPortal', 'Billing Portal - Administrator' => 'BillingPortal');

	/* 5. Array to store values for Slip Categories - Note: V2.0 moving to a Database*/
	$slip_categories = array("Please Select", "Internet", "Contract", "Hardware", "Software", "Shipping", "Vacation", "Sick");

	/* 6. Email Settings (See PHPMailer for config information) */
		$SMTP_Host = "";
		$SMTP_Auth = true;
		$SMTP_Username = "";
		$SMTP_Password = "";
		$SMTP_Security = "";
		$SMTP_Port = 0;
		$SMTP_SendEmailsFromAddress = "";