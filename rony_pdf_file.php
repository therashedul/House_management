<?php include('db_connect.php');?>
<?php $month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m'); ?>
<?php
require_once('tcpdf/config/tcpdf_config.php');
require_once('tcpdf/tcpdf.php');

$i=1;
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Md Rashedul Karim');
$pdf->SetTitle('Rony cost');
$pdf->SetSubject('Cost');
$pdf->SetKeywords('Rony, PDF, Cost, Amount, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' -Pollobi ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($i);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$rony = $conn->query("SELECT  * FROM rony_houses where status = 1 AND date_format(created,'%Y-%m') = '$month_of' ORDER by id desc");

while($row=$rony->fetch_assoc()):
		$house_rent = number_format($row['house_rent']);
	$electricity = number_format($row['electricity']);
	$gass = number_format($row['gass']);
	$water = number_format($row['water']);
	$other = number_format($row['other']);
	$total_cost = number_format($row['total_cost']);
	$rony_part = number_format($row['rony_part']);
	$total_amount = number_format($row['rony_part']+$row['house_rent']);
	$rest_amount = number_format($row['rest_amount']);	

	$html = <<<HTML

	<table>
	<thead>
	<tr>
	<th><p><b><code>Flat No </code> : {$row['rapartmant']}</b></p></th>
	<th></th>
	<th></th>
	<td><p><code> Date </code> : {$row['created']}</p></td>
	</tr>
	</thead>
	</table>
	HTML;

	$html.= <<<HTML
	<div style="margin-top: 25px;"></div>
	<table style="width:100%; height:auto;"  >
	<thead>		
	<tr>
	<th style="border:solid #ccc 1px;">Electricity</th>
	<td style="border:solid #ccc 1px;">{$electricity}</td>
	</tr>
	<tr>
	<th style="border:solid #ccc 1px;">Gass</th>
	<td style="border:solid #ccc 1px;">{$gass}</td>
	</tr>
	<tr>
	<th style="border:solid #ccc 1px;">Water</th>
	<td style="border:solid #ccc 1px;">{$water}</td>
	</tr>
	<tr>
	<th style="border:solid #ccc 1px;">Other</th>
	<td style="border:solid #ccc 1px;">{$other} ( {$row['description']} )</td>
	</tr>
	
	<tr>
	<th style="border:solid #ccc 1px;"><b>Total Cost</b></th>
	<td style="border:solid #ccc 1px;"><b>{$total_cost}</b></td>
	</tr>
	</thead>
	</table>
	HTML;

	
	$html.= <<<HTML
	<div style="margin-top: 25px;"></div>
	<table>
	<thead>
	<tr>	
	<th style="border:solid #ccc 1px;"> House Rent</th>
	<th style="border:solid #ccc 1px;"> Rony Part</th>
	<th style="border:solid #ccc 1px;"> Total Amount</th>
	<th style="border:solid #ccc 1px;"> After Cost Amount</th>
	</tr>
	<tr>	
	<td style="border:solid #ccc 1px;"> {$house_rent}</td>
	<td style="border:solid #ccc 1px;"> {$rony_part}</td>
	<td style="border:solid #ccc 1px;"> {$total_amount}</td>
	<td style="border:solid #ccc 1px;"> <b>{$rest_amount}</b></td>
	</tr>
	</thead>
	</table>
	HTML;

endwhile;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('ronycost.pdf', 'I');
?>






