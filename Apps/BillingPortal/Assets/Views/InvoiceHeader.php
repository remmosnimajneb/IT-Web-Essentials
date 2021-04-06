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
* Invoice Header
*/
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Invoice | <?php echo GetSysConfig("SiteTitle"); ?></title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <style type="text/css">

            /* Quote Styling */
                .quote_alert {
                    background-color: #3989c6;
                    margin-left: auto;
                    margin-right: auto;
                    text-align: center;
                    width: 200px;
                    border-radius: 50px;
                    padding: 15px;
                }
                .quote_alert .fa {
                    color: white;
                    text-align: left;
                    font-size: 36px;
                }
                .quote_alert a {
                    color: white;
                    text-decoration: none;
                }

                .approval {
                    margin-left: auto;
                    margin-right: auto;
                    text-align: center;
                }

                .button-fancy {
                    text-transform: uppercase;
                    border: 2px solid #3989c6;
                    color: #3989c6;
                    padding: 10px 40px;
                    font-size: 14px;
                    font-weight: 700;
                    transition: all 0.3s ease-in;   
                }

                .button-fancy:hover {
                    border: 2px solid #3989c6;
                    background: #3989c6;
                    color: #fff;
                    transition: all 0.3s ease-in-out;   
                }

            /* Invoice Styling */
            #invoice{
                padding: 30px;
            }

            .invoice {
                position: relative;
                background-color: #FFF;
                min-height: 680px;
                padding: 15px
            }

            .invoice header {
                padding: 10px 0;
                margin-bottom: 20px;
                border-bottom: 1px solid #3989c6
            }

            .invoice .company-details {
                text-align: right
            }

            .invoice .company-details .name {
                margin-top: 0;
                margin-bottom: 0
            }

            .invoice .contacts {
                margin-bottom: 20px
            }

            .invoice .invoice-to {
                text-align: left
            }

            .invoice .invoice-to .to {
                margin-top: 0;
                margin-bottom: 0
            }

            .invoice .invoice-details {
                text-align: right
            }

            .invoice .invoice-details .invoice-id {
                margin-top: 0;
                color: #3989c6
            }

            .invoice main {
                padding-bottom: 50px
            }

            .invoice main .thanks {
                margin-top: -100px;
                font-size: 2em;
                margin-bottom: 50px
            }

            .invoice main .notices {
                padding-left: 6px;
                border-left: 6px solid #3989c6
            }

            .invoice main .notices .notice {
                font-size: 1.2em
            }

            .invoice table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px
            }

            .invoice table td,.invoice table th {
                padding: 15px;
                background: #eee;
                border-bottom: 1px solid #fff;
                white-space: pre-line;
            }

            .invoice table th {
                white-space: nowrap;
                font-weight: 400;
                font-size: 16px
            }

            .invoice table td h3 {
                margin: 0;
                font-weight: 400;
                color: #3989c6;
                font-size: 1.2em
            }

            .invoice table .qty,.invoice table .total,.invoice table .unit {
                text-align: right;
                font-size: 1.2em
            }

            .invoice table .no {
                color: #fff;
                font-size: 1.6em;
                background: #3989c6
            }

            .invoice table .unit {
                background: #ddd
            }

            .invoice table .total {
                background: #3989c6;
                color: #fff
            }

            .invoice table tbody tr:last-child td {
                border: none
            }

            .invoice table tfoot td {
                background: 0 0;
                border-bottom: none;
                white-space: nowrap;
                text-align: right;
                padding: 10px 20px;
                font-size: 1.2em;
                border-top: 1px solid #aaa
            }

            .invoice table tfoot tr:first-child td {
                border-top: none
            }

            .invoice table tfoot tr:last-child td {
                color: #3989c6;
                font-size: 1.4em;
                border-top: 1px solid #3989c6
            }

            .invoice table tfoot tr td:first-child {
                border: none
            }

            .invoice footer {
                width: 100%;
                text-align: center;
                color: #777;
                border-top: 1px solid #aaa;
                padding: 8px 0
            }

            @media print {
                .invoice {
                    font-size: 11px!important;
                    overflow: hidden!important
                }

                .invoice footer {
                    position: absolute;
                    bottom: 10px;
                    page-break-after: always
                }

                .invoice>div:last-child {
                    page-break-before: always
                }
            }
            .invoice table .no-bck td{
                background: #fff;
            }
        </style>
    </head>
    <body>
        <div id="invoice">

            <div class="toolbar hidden-print">
                <div class="text-right">
                    <button id="printInvoice" class="btn btn-info" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    <?php if($IsAdmin){ ?><button class="btn btn-info" onclick="SendToClient(<?php echo $SendToID; ?>, '<?php echo $PageName; ?>')"><i class="fa fa-envelope"></i> Email to Client</button>   <?php } ?>
                </div>
                <hr>
            </div>
     
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="<?php echo GetSysConfig("BrandingCompanyURL"); ?>">
                            <img src="<?php echo GetSysConfig("InvoiceBrandingLogo"); ?>" data-holder-rendered="true" />
                        </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="<?php echo GetSysConfig("BrandingCompanyURL"); ?>">
                                <?php echo GetSysConfig("SiteTitle"); ?>
                            </a>
                        </h2>
                        <div><?php echo GetSysConfig("BrandingCompanyAddress"); ?></div>
                        <div><?php echo GetSysConfig("BrandingFooterPhoneNumber"); ?></div>
                        <div><a href="mailto:<?php echo GetSysConfig("InvoiceEmailAddress"); ?>"><?php echo GetSysConfig("InvoiceEmailAddress"); ?></a></div>
                    </div>
                </div>
            </header>