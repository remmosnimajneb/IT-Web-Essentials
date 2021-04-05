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
* Header Page Template
*/

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $PageName . " | " . GetSysConfig("SiteTitle"); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo GetSysConfig("SystemURL"); ?>/Assets/Theme/css/main.css?Version=7" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="<?php echo GetSysConfig("SystemURL"); ?>/Switchboard.php"><?php echo GetSysConfig("SiteTitle"); ?></a></h1>
						<?php
							if(GetUserSecurityLevel($_SESSION['UserID']) > 0){
						?>
							<nav>
								<a href="#menu">Menu</a>
							</nav>
						<?php
							}
						?>
					</header>

				<!-- Menu Nav -->
					<nav id="menu">
						<div class="inner">
							<h2>Menu</h2>
							<ul class="links">
								<?php

									$AllowedSystems = json_decode(GetUserSysPermissions($_SESSION['UserID']), true);
									if($AppName == "BillingPortal" && $AllowedSystems["BillingPortalAdmin"] == "1"){
										?>
											<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Slips">Slips</a></li>
											<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Clients">Clients</a></li>
											<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Contacts">Contacts</a></li>											
											<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Invoices">Invoices</a></li>
										<?php
									} else {
										if($AllowedSystems["BillingPortalUser"] == "1" || $AllowedSystems["BillingPortalAdmin"] == "1"){
											?>
												<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal">Billing Portal</a></li>
											<?php
										}

										if($AllowedSystems["Jots"] == "1"){
											?>
												<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/Jots">Jots</a></li>
											<?php
										}

										if($AllowedSystems["Inventory"] == "1"){
											?>
												<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/Inventory">Inventory</a></li>
											<?php
										}

										if($AllowedSystems["ServiceDesk"] == "1"){
											?>
												<li><a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/ServiceDesk">Service Desk</a></li>
											<?php
										}
									}
								?>
								<a href="<?php echo GetSysConfig("SystemURL"); ?>/Switchboard.php">Switchboard</a> | <a href="<?php echo GetSysConfig("SystemURL"); ?>/System/Auth/Logout.php">Logout</a>
							</ul>
						</div>
					</nav>