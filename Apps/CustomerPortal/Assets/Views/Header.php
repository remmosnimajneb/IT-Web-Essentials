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
* Public Header Template
*/

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo GetSysConfig("SiteTitle"); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/CustomerPortal/Assets/css/main.css?version=4" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	</head>
	<body class="is-preload">

		<!-- Page wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<span class="logo"><a href="<?php echo GetSysConfig("BrandingCompanyURL"); ?>"><?php echo GetSysConfig("SiteTitle"); ?></a></span>
					</header>