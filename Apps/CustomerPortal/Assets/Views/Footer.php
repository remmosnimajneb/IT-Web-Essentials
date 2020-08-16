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
* Footer Layout
*/
?>
					<footer id="footer">
						<div class="inner">
							<ul class="contact">
								<?php
									if(GetSysConfig("BrandingFooterAddress") != NULL){
								?>
										<li class="icon fa-home">
											<strong>Address</strong>
											<?php echo GetSysConfig("BrandingFooterAddress"); ?>
										</li>
								<?php
									}

									if(GetSysConfig("BrandingFooterPhoneNumber") != NULL){
								?>
										<li class="icon fa-phone">
											<strong>Phone</strong>
											<a href="tel:<?php echo GetSysConfig("BrandingFooterPhoneNumber"); ?>"><?php echo GetSysConfig("BrandingFooterPhoneNumber"); ?></a>
										</li>
								<?php
									}

									if(GetSysConfig("BrandingFooterEmail") != NULL){
								?>
										<li class="icon fa-envelope">
											<strong>Email</strong>
											<a href="mailto:<?php echo GetSysConfig("BrandingFooterEmail"); ?>"><?php echo GetSysConfig("BrandingFooterEmail"); ?></a>
										</li>
								<?php
									}
								?>

							</ul>
							<div class="footer_about">
								<h2>About Us</h2>
								<h3><?php echo GetSysConfig("BrandingAboutHeader"); ?></h3>
								<h4><?php echo GetSysConfig("BrandingAboutSubHeader"); ?></h4>
								<a href="<?php echo GetSysConfig("BrandingCompanyURL"); ?>"><button>Learn More About Us</button></a>
							</div>
						</div>
						<div class="copyright">
							&copy; <?php echo GetSysConfig("SiteTitle"); ?>. All Rights Reserved.
						</div>
					</footer>


			</div>

		<!-- Scripts -->
			<script src="Assets/js/jquery.min.js"></script>
			<script src="Assets/js/browser.min.js"></script>
			<script src="Assets/js/breakpoints.min.js"></script>
			<script src="Assets/js/util.js"></script>
			<script src="Assets/js/jquery.scrollex.min.js"></script>
			<script src="Assets/js/jquery.scrolly.min.js"></script>
			<script src="Assets/js/main.js"></script>

	</body>
</html>