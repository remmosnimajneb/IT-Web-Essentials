<?php
/********************************
* Project: IT Web Essentials 
* Code Version: 2.0
* Author: Benjamin Sommer
* Company: The Berman Consulting Group - https://bermangroup.com
* GitHub: https://github.com/remmosnimajneb
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/
/*
* Error Page
*/

/*
	Note: Almost all of this is hardcoded as it's intended to handle even core errors such as Database Connection Failures and Install Failures which may cuase major issues with core system functions, and must work - pretty much, if nothing else does.
*/
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Error</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/Assets/Theme/css/main.css?Version=6" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="/Switchboard.php">Back to Switchboard</a></h1>
						<nav>
							<a href="#menu">Menu</a>
						</nav>
					</header>
					<!-- Main -->
						<div id="main" style="width: 95vw;">
						<!-- Content -->
							<section id="content" class="default">
								<header class="major">
									<h2>Error</h2>
								</header>
								<div class="content" style="text-align: center;">
									<h3>Oops! Something went wrong with that!</h3>
									<h4>Error Message: <?php if(isset($_GET['Error'])) echo $_GET['Error']; ?></h4>
										<br>
									<a href="/Switchboard.php">Switchboard</a>
								</div>
							</section>
						</div>

					<!-- Footer -->
					<section id="footer">
						<div class="copyright">
							<p>IT Web Essentials Version 2.0 By <a href="https://github.com/remmosnimajneb">@remmosnimajneb</a></p>
						</div>
					</section>
			</div>


		<!-- Scripts -->
			<script src="Assets/Theme/js/jquery.min.js"></script>
			<script src="Assets/Theme/js/browser.min.js"></script>
			<script src="Assets/Theme/js/breakpoints.min.js"></script>
			<script src="Assets/Theme/js/util.js"></script>
			<script src="Assets/Theme/js/main.js"></script>
	</body>
</html>