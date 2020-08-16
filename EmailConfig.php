<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/* SQL Config File */

	/* Enable or Disable using External SMTP (False will use PHP's mail()) */
	DEFINE('USE_EXTERNAL_STMP', FALSE);

	/* Config */
	DEFINE('SMTP_Host', '');
	DEFINE('SMTP_Auth', TRUE);
	DEFINE('SMTP_Username', '');
	DEFINE('SMTP_Password', '');
	DEFINE('SMTP_Port', 0);
	DEFINE('SMTP_Security', 'tls');
	DEFINE('SMTP_SendEmailsFromAddress', ''); 