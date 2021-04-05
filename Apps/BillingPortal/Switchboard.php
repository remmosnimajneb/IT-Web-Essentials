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
* Switchboard for Billing Portal
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalUser";
$PageName = "Billing Portal";

/* Include System Functions */
require_once("../../InitSystem.php");

/* If User doesn't have Admin Access, just go to Slips directly */
if(GetUserSecurityLevel($_SESSION['UserID']) == 1){
	header('Location: ' . GetSysConfig("SystemURL") . '/Apps/BillingPortal/Slips/');
	die();
}

/* Get Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
<div id="main" style="width: 95vw;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Billing Portal</h2>
		</header>
		<div class="content">
			<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
			<section style="text-align: center;">
				<div class="row" style="justify-content: center;">
					<div class="column">
						<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Slips" class="button large">Slips</a>
					</div>

					<?php if( json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["BillingPortalAdmin"] == "1" ){ ?>
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Subscriptions" class="button large">Subscriptions</a>
						</div>
					<?php } ?>
				</div>
					<hr>
				<?php if( json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["BillingPortalAdmin"] == "1" ){ ?>
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes" class="button large">Price Quotes</a>
						</div>
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Invoices" class="button large">Invoices</a>
						</div>
					</div>
						<hr>
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Clients" class="button large">Clients</a>
						</div>
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Contacts" class="button large">Contacts</a>
						</div>
					</div>
						<hr>
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Reports" class="button large">Reports</a>
						</div>
					</div>
				<?php } ?>
			</section>
		</div>
	</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>