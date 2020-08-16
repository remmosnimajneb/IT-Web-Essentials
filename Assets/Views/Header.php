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
* Header Page Template
*/

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $PageName . " | " . GetSysConfig("SiteTitle"); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo GetSysConfig("SystemURL"); ?>/Assets/Theme/css/main.css?Version=6" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="/Switchboard.php"><?php echo GetSysConfig("SiteTitle"); ?></a></h1>
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

				<!-- Menu -->
					<nav id="menu">
						<div class="inner">
							<h2>Menu</h2>
							<ul class="links">
								<?php

									$AllowedSystems = json_decode(GetUserSysPermissions($_SESSION['UserID']), true);
									if($AppName == "BillingPortal" && $AllowedSystems["BillingPortalAdmin"] == "1"){
										?>
											<li><a href="/Apps/BillingPortal/Slips">Slips</a></li>
											<li><a href="/Apps/BillingPortal/Clients">Clients</a></li>
											<li><a href="/Apps/BillingPortal/Contacts">Contacts</a></li>											
											<li><a href="/Apps/BillingPortal/Invoices">Invoices</a></li>
										<?php
									} else {
										if($AllowedSystems["BillingPortalUser"] == "1" || $AllowedSystems["BillingPortalAdmin"] == "1"){
											?>
												<li><a href="/Apps/BillingPortal">Billing Portal</a></li>
											<?php
										}

										if($AllowedSystems["Jots"] == "1"){
											?>
												<li><a href="/Apps/Jots">Jots</a></li>
											<?php
										}

										if($AllowedSystems["Inventory"] == "1"){
											?>
												<li><a href="/Apps/Inventory">Inventory</a></li>
											<?php
										}

										if($AllowedSystems["ServiceDesk"] == "1"){
											?>
												<li><a href="/Apps/ServiceDesk">Service Desk</a></li>
											<?php
										}
									}
								?>
								<a href="/Switchboard.php">Switchboard</a> | <a href="/System/Auth/Logout.php">Logout</a>
							</ul>
						</div>
					</nav>