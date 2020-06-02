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
* Invoice Footer
*/
?>		        
		        </div>
		        <?php //<!--DO NOT DELETE THIS <div>. IT is responsible for showing footer always at the bottom--> ?>
		        <div></div>
		    </div>
		</div>
	</body>
	<script type="text/javascript">
		function SendInvoice(InvoiceID){
			if(confirm("This will send the invoice to the Client. Are you sure you want to send?")){
				window.location.href = "SendInvoice.php?ID=" + InvoiceID;
			}
		}
	</script>
</html>