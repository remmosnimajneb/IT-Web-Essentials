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
* Invoices Overview
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Reports";

/* Include System Functions */
require_once("../../../InitSystem.php");
		
	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');

?>
<div id="main" style="width: 95vw;">
	<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Reports</h2>
		</header>
		<div class="content">

			<!-- Slip Reports -->
				<h2>Slip Reports</h2>

			<form action="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Reports/ExportReport.php" method="POST">
					
					<input type="hidden" name="ReportType" value="Slips">

					<fieldset id="Slip_SlipReports">
					
					<!-- Columns -->
					<h3>Columns to Export</h3>
						<div class="row">
							<div class="column">
								<h4>Slip Information</h4>

									<input type="checkbox" id="Slip_SlipsAll" name="Slip_Slips[]" value="SlipsAll">
										<label for="Slip_SlipsAll"> All</label>
										<br>
									<input type="checkbox" id="Slip_SlipID" name="Slip_SlipColumns[]" value="SlipID">
										<label for="Slip_SlipID"> Slip ID</label>
										<br>
									<input type="checkbox" id="Slip_TSType" name="Slip_SlipColumns[]" value="TSType">
										<label for="Slip_TSType"> Slip Type</label>
										<br>
									<input type="checkbox" id="Slip_StartDate" name="Slip_SlipColumns[]" value="StartDate">
										<label for="Slip_StartDate"> Start Date</label>
										<br>
									<input type="checkbox" id="Slip_EndDate" name="Slip_SlipColumns[]" value="EndDate">
										<label for="Slip_EndDate"> End Date</label>
										<br>
									<input type="checkbox" id="Slip_Hours" name="Slip_SlipColumns[]" value="Hours">
										<label for="Slip_Hours"> Hours</label>
										<br>
									<input type="checkbox" id="Slip_DNB" name="Slip_SlipColumns[]" value="DNB">
										<label for="Slip_DNB"> DNB Hours</label>
										<br>
									<input type="checkbox" id="Slip_Description" name="Slip_SlipColumns[]" value="Description">
										<label for="Slip_Description"> Description</label>
										<br>
									<input type="checkbox" id="Slip_InternalNotes" name="Slip_SlipColumns[]" value="InternalNotes">
										<label for="Slip_InternalNotes"> Internal Notes</label>
										<br>
									<input type="checkbox" id="Slip_Price" name="Slip_SlipColumns[]" value="Price">
										<label for="Slip_Price"> Price (Expense)</label>
										<br>
									<input type="checkbox" id="Slip_Quantity" name="Slip_SlipColumns[]" value="Quantity">
										<label for="Slip_Quantity"> Quantity (Expense)</label>
										<br>
									<input type="checkbox" id="Slip_SlipStatus" name="Slip_SlipColumns[]" value="SlipStatus">
										<label for="Slip_SlipStatus"> Slip Status</label>
										<br>
							</div>

							<div class="column">
								<h4>Invoice Information</h4>

									<input type="checkbox" id="Slip_InvoiceAll" name="Slip_Invoices[]" value="InvoiceAll">
										<label for="Slip_InvoiceAll"> All</label>
										<br>
									<input type="checkbox" id="Slip_InvoiceID" name="Slip_InvoiceColumns[]" value="InvoiceID">
										<label for="Slip_InvoiceID"> Invoice ID</label>
										<br>
									<input type="checkbox" id="Slip_InvoiceDate" name="Slip_InvoiceColumns[]" value="InvoiceDate">
										<label for="Slip_InvoiceDate"> Invoice Date</label>
										<br>
									<input type="checkbox" id="Slip_InvoiceStatus" name="Slip_InvoiceColumns[]" value="InvoiceStatus">
										<label for="Slip_InvoiceStatus"> Invoice Status</label>
										<br>
									<input type="checkbox" id="Slip_PaymentStatus" name="Slip_InvoiceColumns[]" value="PaymentStatus">
										<label for="Slip_PaymentStatus"> Payment Status</label>
										<br>
							</div>

							<div class="column">
								<h4>Client Information</h4>

									<input type="checkbox" id="Slip_ClientAll" name="Slip_Clients[]" value="ClientAll">
										<label for="Slip_ClientAll"> All</label>
										<br>
									<input type="checkbox" id="Slip_ClientID" name="Slip_ClientColumns[]" value="ClientID">
										<label for="Slip_ClientID"> Client ID</label>
										<br>
									<input type="checkbox" id="Slip_ClientName" name="Slip_ClientColumns[]" value="ClientName">
										<label for="Slip_ClientName"> Client Name</label>
										<br>
									<input type="checkbox" id="Slip_ClientSlug" name="Slip_ClientColumns[]" value="ClientSlug">
										<label for="Slip_ClientSlug"> Client Slug</label>
										<br>
									<input type="checkbox" id="Slip_StreetName" name="Slip_ClientColumns[]" value="StreetName">
										<label for="Slip_StreetName"> Street Name</label>
										<br>
									<input type="checkbox" id="Slip_City" name="Slip_ClientColumns[]" value="City">
										<label for="Slip_City"> City</label>
										<br>
									<input type="checkbox" id="Slip_State" name="Slip_ClientColumns[]" value="State">
										<label for="Slip_State"> State</label>
										<br>
									<input type="checkbox" id="Slip_ZIP" name="Slip_ClientColumns[]" value="ZIP">
										<label for="Slip_ZIP"> ZIP</label>
										<br>
									<input type="checkbox" id="Slip_Email" name="Slip_ClientColumns[]" value="Email">
										<label for="Slip_Email"> Email</label>
										<br>
									<input type="checkbox" id="Slip_Phone" name="Slip_ClientColumns[]" value="Phone">
										<label for="Slip_Phone"> Phone</label>
										<br>
									<input type="checkbox" id="Slip_Fax" name="Slip_ClientColumns[]" value="Fax">
										<label for="Slip_Fax"> Fax</label>
										<br>
									<input type="checkbox" id="Slip_FlatRate" name="Slip_ClientColumns[]" value="FlatRate">
										<label for="Slip_FlatRate"> Flat Rate</label>
										<br>
									<input type="checkbox" id="Slip_HourlyDefaultRate" name="Slip_ClientColumns[]" value="HourlyDefaultRate">
										<label for="Slip_HourlyDefaultRate"> Hourly Default Rate</label>
										<br>
									<input type="checkbox" id="Slip_Notes" name="Slip_ClientColumns[]" value="Notes">
										<label for="Slip_Notes"> Notes</label>
										<br>
							</div>

							<div class="column">
								<h4>Consultant Information</h4>

									<input type="checkbox" id="Slip_UserAll" name="Slip_Users[]" value="UserAll">
										<label for="Slip_UserAll"> All</label>
										<br>
									<input type="checkbox" id="Slip_UserID" name="Slip_UserColumns[]" value="UserID">
										<label for="Slip_UserID"> User ID</label>
										<br>
									<input type="checkbox" id="Slip_Name" name="Slip_UserColumns[]" value="Name">
										<label for="Slip_Name"> Name</label>
										<br>
									<input type="checkbox" id="Slip_ConsultantSlug" name="Slip_UserColumns[]" value="ConsultantSlug">
										<label for="Slip_ConsultantSlug"> Consultant Slug</label>
										<br>
									<input type="checkbox" id="Slip_StreetName" name="Slip_UserColumns[]" value="StreetName">
										<label for="Slip_StreetName"> Street Name</label>
										<br>
									<input type="checkbox" id="Slip_City" name="Slip_UserColumns[]" value="City">
										<label for="Slip_City"> City</label>
										<br>
									<input type="checkbox" id="Slip_State" name="Slip_UserColumns[]" value="State">
										<label for="Slip_State"> State</label>
										<br>
									<input type="checkbox" id="Slip_ZIP" name="Slip_UserColumns[]" value="ZIP">
										<label for="Slip_ZIP"> ZIP</label>
										<br>
									<input type="checkbox" id="Slip_Email" name="Slip_UserColumns[]" value="Email">
										<label for="Slip_Email"> Email</label>
										<br>
									<input type="checkbox" id="Slip_Phone" name="Slip_UserColumns[]" value="Phone">
										<label for="Slip_Phone"> Phone</label>
										<br>
									<input type="checkbox" id="Slip_Fax" name="Slip_UserColumns[]" value="Fax">
										<label for="Slip_Fax"> Fax</label>
										<br>
									<input type="checkbox" id="Slip_Notes" name="Slip_UserColumns[]" value="Notes">
										<label for="Slip_Notes"> Notes</label>
										<br>
							</div>
						</div>
						<div class="row">
							<div class="column">
								<h4>Filters</h4>
									<input type="hidden" name="Slip_NumberOfFilters" id="Slip_NumberOfFilters" value="0">
									<section id="Slip_Filters" style="width: 60vw;">
										
									</section>
									<br>
									<button id="Slip_AddFilter"><span class="fa fa-plus"></span>&nbsp;Add Filter</button>
							</div>
						</div>
							<br>
						<input type="submit" name="submit" value="Export">
				</fieldset>
			</form>
				<hr>
			<!-- Invoice Reports -->
				<h2>Invoice Reports</h2>

			<form action="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Reports/ExportReport.php" method="POST">
					
					<input type="hidden" name="ReportType" value="Invoice">

					<fieldset id="Invoice_SlipReports">
					
					<!-- Columns -->
					<h3>Columns to Export</h3>
						<div class="row">

							<div class="column">
								<h4>Invoice Information</h4>

									<input type="checkbox" id="Invoice_InvoiceAll" name="Invoice_Invoices[]" value="InvoiceAll">
										<label for="Invoice_InvoiceAll"> All</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceID" name="Invoice_InvoiceColumns[]" value="InvoiceID">
										<label for="Invoice_InvoiceID"> Invoice ID</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceDate" name="Invoice_InvoiceColumns[]" value="InvoiceDate">
										<label for="Invoice_InvoiceDate"> Invoice Date</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceType" name="Invoice_InvoiceColumns[]" value="InvoiceType">
										<label for="Invoice_InvoiceType"> Invoice Type</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceFlatRateMonths" name="Invoice_InvoiceColumns[]" value="FlatRateMonths">
										<label for="Invoice_InvoiceFlatRateMonths"> Invoice Flat Rate Months</label>
										<br>
									<input type="checkbox" id="Invoice_InvoicePreviousBalance" name="Invoice_InvoiceColumns[]" value="PreviousBalance">
										<label for="Invoice_InvoicePreviousBalance"> Invoice Previous Balance</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceDiscountType" name="Invoice_InvoiceColumns[]" value="DiscountType">
										<label for="Invoice_InvoiceDiscountType"> Invoice Discount Type</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceStatus" name="Invoice_InvoiceColumns[]" value="InvoiceStatus">
										<label for="Invoice_InvoiceStatus"> Invoice Status</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceTotal" name="Invoice_InvoiceColumns[]" value="InvoiceTotal">
										<label for="Invoice_InvoiceTotal"> Invoice Total Due</label>
										<br>
									<input type="checkbox" id="Invoice_PaymentStatus" name="Invoice_InvoiceColumns[]" value="PaymentStatus">
										<label for="Invoice_PaymentStatus"> Payment Status</label>
										<br>
									<input type="checkbox" id="Invoice_PaymentAmount" name="Invoice_InvoiceColumns[]" value="PaymentAmount">
										<label for="Invoice_PaymentAmount"> Payment Amount</label>
										<br>
									<input type="checkbox" id="Invoice_PaymentNotes" name="Invoice_InvoiceColumns[]" value="PaymentNotes">
										<label for="Invoice_PaymentNotes"> Payment Notes</label>
										<br>
									<input type="checkbox" id="Invoice_PaymentDate" name="Invoice_InvoiceColumns[]" value="PaymentDate">
										<label for="Invoice_PaymentDate"> Payment Date</label>
										<br>
									<input type="checkbox" id="Invoice_InternalNotes" name="Invoice_InvoiceColumns[]" value="InternalNotes">
										<label for="Invoice_InternalNotes"> Internal Notes</label>
										<br>
									<input type="checkbox" id="Invoice_InvoiceNotes" name="Invoice_InvoiceColumns[]" value="InvoiceNotes">
										<label for="Invoice_InvoiceNotes"> Invoice Notes</label>
										<br>
							</div>

							<div class="column">
								<h4>Client Information</h4>

									<input type="checkbox" id="Invoice_ClientAll" name="Invoice_Clients[]" value="ClientAll">
										<label for="Invoice_ClientAll"> All</label>
										<br>
									<input type="checkbox" id="Invoice_ClientID" name="Invoice_ClientColumns[]" value="ClientID">
										<label for="Invoice_ClientID"> Client ID</label>
										<br>
									<input type="checkbox" id="Invoice_ClientName" name="Invoice_ClientColumns[]" value="ClientName">
										<label for="Invoice_ClientName"> Client Name</label>
										<br>
									<input type="checkbox" id="Invoice_ClientSlug" name="Invoice_ClientColumns[]" value="ClientSlug">
										<label for="Invoice_ClientSlug"> Client Slug</label>
										<br>
									<input type="checkbox" id="Invoice_StreetName" name="Invoice_ClientColumns[]" value="StreetName">
										<label for="Invoice_StreetName"> Street Name</label>
										<br>
									<input type="checkbox" id="Invoice_City" name="Invoice_ClientColumns[]" value="City">
										<label for="Invoice_City"> City</label>
										<br>
									<input type="checkbox" id="Invoice_State" name="Invoice_ClientColumns[]" value="State">
										<label for="Invoice_State"> State</label>
										<br>
									<input type="checkbox" id="Invoice_ZIP" name="Invoice_ClientColumns[]" value="ZIP">
										<label for="Invoice_ZIP"> ZIP</label>
										<br>
									<input type="checkbox" id="Invoice_Email" name="Invoice_ClientColumns[]" value="Email">
										<label for="Invoice_Email"> Email</label>
										<br>
									<input type="checkbox" id="Invoice_Phone" name="Invoice_ClientColumns[]" value="Phone">
										<label for="Invoice_Phone"> Phone</label>
										<br>
									<input type="checkbox" id="Invoice_Fax" name="Invoice_ClientColumns[]" value="Fax">
										<label for="Invoice_Fax"> Fax</label>
										<br>
									<input type="checkbox" id="Invoice_FlatRate" name="Invoice_ClientColumns[]" value="FlatRate">
										<label for="Invoice_FlatRate"> Flat Rate</label>
										<br>
									<input type="checkbox" id="Invoice_HourlyDefaultRate" name="Invoice_ClientColumns[]" value="HourlyDefaultRate">
										<label for="Invoice_HourlyDefaultRate"> Hourly Default Rate</label>
										<br>
									<input type="checkbox" id="Invoice_Notes" name="Invoice_ClientColumns[]" value="Notes">
										<label for="Invoice_Notes"> Notes</label>
										<br>
							</div>

						</div>
						<div class="row">
							<div class="column">
								<h4>Filters</h4>
									<input type="hidden" name="Invoice_NumberOfFilters" id="Invoice_NumberOfFilters" value="0">
									<section id="Invoice_Filters" style="width: 60vw;">
										
									</section>
									<br>
									<button id="Invoice_AddFilter"><span class="fa fa-plus"></span>&nbsp;Add Filter</button>
							</div>
						</div>
							<br>
						<input type="submit" name="submit" value="Export">
				</fieldset>
			</form>

</div>
<script type="text/javascript">


	/* Checkbox Handling */

		/* Slips */
		$('#Slip_SlipsAll').change(function() {
	        if(this.checked) {
	            /* Check All other Slips Boxes */
	            $('input[name=Slip_SlipColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Slips Boxes */
	        	$('input[name=Slip_SlipColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked

	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Slip_SlipColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Slip_SlipsAll').is(":checked")){
	    			$('#Slip_SlipsAll').prop("checked", false);
	    		}

	    	});

	    /* Invoice */
		$('#Slip_InvoiceAll').change(function() {
	        if(this.checked) {
	            /* Check All other Boxes */
	            $('input[name=Slip_InvoiceColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Boxes */
	        	$('input[name=Slip_InvoiceColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked
	    	
	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Slip_InvoiceColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Slip_InvoiceAll').is(":checked")){
	    			$('#Slip_InvoiceAll').prop("checked", false);
	    		}

	    	});

	    /* Client */
		$('#Slip_ClientAll').change(function() {
	        if(this.checked) {
	            /* Check All other Boxes */
	            $('input[name=Slip_ClientColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Boxes */
	        	$('input[name=Slip_ClientColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked
	    	
	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Slip_ClientColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Slip_ClientAll').is(":checked")){
	    			$('#Slip_ClientAll').prop("checked", false);
	    		}

	    	});

	    /* Consultant */
		$('#Slip_UserAll').change(function() {
	        if(this.checked) {
	            /* Check All other Boxes */
	            $('input[name=Slip_UserColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Boxes */
	        	$('input[name=Slip_UserColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked
	    	
	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Slip_UserColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Slip_UserAll').is(":checked")){
	    			$('#Slip_UserAll').prop("checked", false);
	    		}

	    	});

	/* Invoice Checkox Handling */
		/* Invoice */
		$('#Invoice_InvoiceAll').change(function() {
	        if(this.checked) {
	            /* Check All other Boxes */
	            $('input[name=Invoice_InvoiceColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Boxes */
	        	$('input[name=Invoice_InvoiceColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked

	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Invoice_InvoiceColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Invoice_InvoiceAll').is(":checked")){
	    			$('#Invoice_InvoiceAll').prop("checked", false);
	    		}

	    	});

	    /* Client */
		$('#Invoice_ClientAll').change(function() {
	        if(this.checked) {
	            /* Check All other Boxes */
	            $('input[name=Invoice_ClientColumns\\[\\]]').prop("checked", true);
	        } else {
	        	/* Un-Check All other Boxes */
	        	$('input[name=Invoice_ClientColumns\\[\\]]').prop("checked", false);
	        }
	    });

	    	/* Handle Uncheck all if one box is unchecked

	    	   No need to handle the other way since practically, if all the boxes are checked
	    	   it's really the same thing as checking "All" */
	    	$('input[name=Invoice_ClientColumns\\[\\]]').change(function() {

	    		if(!this.checked && $('#Invoice_ClientAll').is(":checked")){
	    			$('#Invoice_ClientAll').prop("checked", false);
	    		}

	    	});

	/* Filters */

		/* Add Filter */
		$( "#Slip_AddFilter" ).click(function() {

			$("#Slip_NumberOfFilters").val(parseInt($("#Slip_NumberOfFilters").val())+1);

			$("#Slip_Filters").append('<section class="Slip_Filter_' + $("#Slip_NumberOfFilters").val() + '">	<div class="row">		<div class="column">			Column: <select required="required" name="Filter_' + $("#Slip_NumberOfFilters").val() + '_Column" id="Filter_' + $("#Slip_NumberOfFilters").val() + '_Column">				<option value="-1" disabled="disabled" selected="selected">-- Select --</option>			<option value="Slip" disabled="disabled">Slip</option>					<option value="S.`SlipID`"> - Slip ID</option>					<option value="S.`TSType`"> - Slip Type</option>					<option value="S.`StartDate`"> - Start Date</option>					<option value="S.`EndDate`"> - End Date</option>					<option value="S.`Hours`"> - Hours</option>					<option value="S.`DNB`"> - DNB Hours</option>					<option value="S.`Price`"> - Price</option>					<option value="S.`Quantity`"> - Quantity</option>					<option value="S.`SlipStatus`"> - Slip Status</option><option value="Invoice" disabled="disabled">Invoice</option>					<option value="I.`InvoiceID`"> - Invoice ID</option>					<option value="I.`InvoiceDate`"> - Invoice Date</option>					<option value="I.`InvoiceStatus`"> - Invoice Status</option>					<option value="I.`PaymentStatus`"> - Payment Status</option><option value="Client" disabled="disabled">Client</option>					<option value="C.`ClientID`"> - Client ID</option>					<option value="C.`ClientSlug`"> - Client Slug</option>									<option value="Consultant" disabled="disabled">Consultant</option>					<option value="U.`UserID`"> - User ID</option>					<option value="U.`ConsultantSlug`"> - Consultant Slug</option>			</select>		</div>		<div class="column">			Condition: <select name="Filter_' + $("#Slip_NumberOfFilters").val() + '_Condition" required="required" id="Filter_' + $("#Slip_NumberOfFilters").val() + '_Condition">				<option value="-1" disabled="disabled" selected="selected">-- Select --</option>				<option value="Equal">Equal</option>				<option value="LIKE">Like</option>				<option value="%Like%">%Like%</option>				<option value="LessThan">Less Than (Date - Before)</option>				<option value="MoreThan">More Than (Date - After)</option>				<option value="NotLike">Not Like</option>			</select>		</div>		<div class="column">			Value: <input type="text" required="required" name="Filter_' + $("#Slip_NumberOfFilters").val() + '_Value">			</div>		<div class="column" style="display: flex;align-items: end;justify-content: center;">			<button id="Slip_RemoveFilter_' + $("#Slip_NumberOfFilters").val() + '" onclick="Slip_RemoveFilter(' + $("#Slip_NumberOfFilters").val() + ')" style="margin: 0px;width: 180px;"><span class="fa fa-minus"></span>&nbsp;Remove</button>		</div></section>');

			// Disable removing any Filters above it
			if($("#Slip_NumberOfFilters").val() > 1){
				for (var i = parseInt($("#Slip_NumberOfFilters").val())-1; i > 0; i--) {
					$("#Slip_RemoveFilter_" + i).prop("disabled", true);
				}
			}

		});

	/* Remove Filter */
		function Slip_RemoveFilter(Filter_Number){

			$(".Slip_Filter_" + Filter_Number).remove();

			$("#Slip_RemoveFilter_" + (Filter_Number-1)).prop("disabled", false);

			$("#Slip_NumberOfFilters").val(parseInt($("#Slip_NumberOfFilters").val())-1);

		}


	/* Invoice Add and remove */

		/* Add Filter */
		$( "#Invoice_AddFilter" ).click(function() {

			$("#Invoice_NumberOfFilters").val(parseInt($("#Invoice_NumberOfFilters").val())+1);

			$("#Invoice_Filters").append('<section class="Invoice_Filter_' + $("#Invoice_NumberOfFilters").val() + '">	<div class="row">		<div class="column">			Column: <select required="required" name="Filter_' + $("#Invoice_NumberOfFilters").val() + '_Column" id="Filter_' + $("#Invoice_NumberOfFilters").val() + '_Column">				<option value="-1" disabled="disabled" selected="selected">-- Select --</option><option value="Invoice" disabled="disabled">Invoice</option>					<option value="I.`InvoiceID`"> - Invoice ID</option>					<option value="I.`InvoiceDate`"> - Invoice Date</option>					<option value="I.`InvoiceStatus`"> - Invoice Status</option><option value="I.`InvoiceType`"> - Invoice Type</option>					<option value="I.`PaymentStatus`"> - Payment Status</option><option value="Client" disabled="disabled">Client</option>					<option value="C.`ClientID`"> - Client ID</option>					<option value="C.`ClientSlug`"> - Client Slug</option></select>		</div>		<div class="column">			Condition: <select name="Filter_' + $("#Invoice_NumberOfFilters").val() + '_Condition" required="required" id="Filter_' + $("#Invoice_NumberOfFilters").val() + '_Condition">				<option value="-1" disabled="disabled" selected="selected">-- Select --</option>				<option value="Equal">Equal</option>				<option value="LIKE">Like</option>				<option value="%Like%">%Like%</option>				<option value="LessThan">Less Than (Date - Before)</option>				<option value="MoreThan">More Than (Date - After)</option>				<option value="NotLike">Not Like</option>			</select>		</div>		<div class="column">			Value: <input type="text" required="required" name="Filter_' + $("#Invoice_NumberOfFilters").val() + '_Value">			</div>		<div class="column" style="display: flex;align-items: end;justify-content: center;">			<button id="Invoice_RemoveFilter_' + $("#Invoice_NumberOfFilters").val() + '" onclick="Invoice_RemoveFilter(' + $("#Invoice_NumberOfFilters").val() + ')" style="margin: 0px;width: 180px;"><span class="fa fa-minus"></span>&nbsp;Remove</button>		</div></section>');

			// Disable removing any Filters above it
			if($("#Invoice_NumberOfFilters").val() > 1){
				for (var i = parseInt($("#Invoice_NumberOfFilters").val())-1; i > 0; i--) {
					$("#Invoice_RemoveFilter_" + i).prop("disabled", true);
				}
			}

		});

	/* Remove Filter */
		function Invoice_RemoveFilter(Filter_Number){

			$(".Invoice_Filter_" + Filter_Number).remove();

			$("#Invoice_RemoveFilter_" + (Filter_Number-1)).prop("disabled", false);

			$("#Invoice_NumberOfFilters").val(parseInt($("#Invoice_NumberOfFilters").val())-1);

		}


</script>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>