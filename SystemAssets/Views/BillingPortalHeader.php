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
* Header Page Template
*/

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $PageName . " | " . $SiteTitle; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo $SystemPublicURL; ?>/SystemAssets/AdminAssets/css/main.css?Version=6" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="/System/Switchboard.php"><?php echo $SiteTitle; ?></a></h1>
						<nav>
							<a href="#menu">Menu</a>
						</nav>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<div class="inner">
							<h2>Menu</h2>
							<?php
								if(Authenticate()){
							?>
								<ul class="links">
									<li><a href='/System/Switchboard.php'>Switchboard</a></li>

									<?php 
										//Show different options based on System
										if($CurrentSystem == "BillingPortal"){
											?>
												<li><a href="/System/BillingPortal/Slips">Slips</a></li>
												<li><a href="/System/BillingPortal/Clients">Clients</a></li>
												<li><a href="/System/BillingPortal/Invoices">Invoices</a></li>
											<?php
										} else {
											?>
												<li><a href='/System/Jots'>Jots</a></li>
												<li><a href='/System/BillingPortal'>Billing Portal</a></li>
												<li><a href='/System/SystemUsers'>System Users</a></li>
											<?php
										}
									?>
									<a href="/System/Auth/Logout.php">Logout</a>
								</ul>
							<?php
								}
							?>
						</div>
					</nav>