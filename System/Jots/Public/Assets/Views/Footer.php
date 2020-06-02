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

/* Footer Layout
*/
?>
					<footer id="footer">
						<div class="inner">
							<ul class="contact">
								<?php
									if($BrandingFooterAddress != NULL){
								?>
										<li class="icon fa-home">
											<strong>Address</strong>
											<?php echo $BrandingFooterAddress; ?>
										</li>
								<?php
									}

									if($BrandingFooterPhoneNumber != NULL){
								?>
										<li class="icon fa-phone">
											<strong>Phone</strong>
											<a href="tel:<?php echo $BrandingFooterPhoneNumber; ?>"><?php echo $BrandingFooterPhoneNumber; ?></a>
										</li>
								<?php
									}

									if($BrandingFooterEmail != NULL){
								?>
										<li class="icon fa-envelope">
											<strong>Email</strong>
											<a href="mailto:<?php echo $BrandingFooterEmail; ?>"><?php echo $BrandingFooterEmail; ?></a>
										</li>
								<?php
									}
								?>

							</ul>
							<div class="footer_about">
								<h2>About Us</h2>
								<h3><?php echo $BrandingAboutHeader; ?></h3>
								<h4><?php echo $BrandingAboutSubHeader; ?></h4>
								<a href="<?php echo $BrandingCompanyURL; ?>"><button>Learn More About Us</button></a>
							</div>
						</div>
						<div class="copyright">
							&copy; <?php echo $FooterCopyright; ?>. All Rights Reserved.
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