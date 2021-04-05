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
		function SendToClient(ID, Type){
			if(confirm("This will send the invoice/quote to the Client. Are you sure you want to send?")){
				window.location.href = "<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Shared/SendToClient.php?ID=" + ID + "&Type=" + Type;
			}
		}
	</script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
		/* Sign Pad */
		
		var canvas = document.querySelector("canvas");
		var signaturePad = new SignaturePad(canvas);
		var signaturePad = new SignaturePad(canvas, {
		    minWidth: 2,
		    maxWidth: 2,
		    penColor: "rgb(0, 0, 0)"
		});

		function ClearPad(){
			signaturePad.clear();
		}


		function ValidateSign(){

			/* Check Sign not empty */
			if(signaturePad.isEmpty()){
				return false;
			}

			$("#Base64SignPadVal").val(signaturePad.toDataURL());

			return true;

		}
	</script>
</html>