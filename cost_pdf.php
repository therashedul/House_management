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
$pdf->SetTitle('House cost');
$pdf->SetSubject('Cost');
$pdf->SetKeywords('House, PDF, Cost, Amount, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' -Pullobi ', PDF_HEADER_STRING);

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
$payment = $conn->query("SELECT sum(amount) as paid FROM payments 
	where date_format(date_created,'%Y-%m') = '$month_of' order by unix_timestamp(date_created)  asc");
$fullmonth =   $payment->num_rows > 0 ? $payment->
fetch_array()['paid'] : 0;	

if(isset($fullmonth) ):
	$i = 1;
	$slip = $conn->query("SELECT  * FROM costs where status = 1 AND date_format(created,'%Y-%m') = '$month_of' ORDER by id desc");
	while($row=$slip->fetch_assoc()):
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['created']." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$payable = $row['total_amount'] * $months;
		$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
		$last_payment = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$outstanding = $payable - $paid;							
		$cost_rent = ($fullmonth-$row['total_amount']);
		$val = ($fullmonth-$row['total_amount'])/2;

		$electricity = number_format($row['electricity']);
		$gass = number_format($row['gas']);
		$water = number_format($row['water']);
		$other = number_format($row['other']);
		$gard = number_format($row['parent']);
		$total_amount = number_format($row['total_amount']);	
		$holemonth = number_format($fullmonth);
		$cost_rent = number_format($cost_rent);
		$part = number_format($val,2);

		$html = <<<HTML

		<table>
		<thead>
		<tr>
		<th><h2><code>Our House Cost</code></h2></th>		
		<th></th>
		<td><p><code> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Date </code> : {$row['created']}</p></td>
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
		<th style="border:solid #ccc 1px;">Gard</th>
		<td style="border:solid #ccc 1px;">{$gard}</td>
		</tr>
		<tr>
		<th style="border:solid #ccc 1px;">Other</th>
		<td style="border:solid #ccc 1px;">{$other} ( {$row['description']} )</td>
		</tr>

		<tr>
		<th style="border:solid #ccc 1px;"><b>Total Cost</b></th>
		<td style="border:solid #ccc 1px;"><b>{$total_amount}</b></td>
		</tr>
		</thead>
		</table>
		HTML;


		$html.= <<<HTML
		<div style="margin-top: 25px;"></div>
		<table>
		<thead>
		<tr>	
		<th style="border:solid #ccc 1px;"> Total House Rent</th>
		<th style="border:solid #ccc 1px;"> After cost</th>
		<th style="border:solid #ccc 1px;"> Rony Part</th>
		<th style="border:solid #ccc 1px;"> Rupon Part</th>
		</tr>
		<tr>	
		<td style="border:solid #ccc 1px;"><b> {$holemonth} </b></td>		
		<td style="border:solid #ccc 1px;"><b> {$cost_rent} </b></td>		
		<td style="border:solid #ccc 1px;"> {$part}</td>
		<td style="border:solid #ccc 1px;"> {$part}</td>
		</tr>
		</thead>
		</table>
		HTML;

	endwhile;
endif;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('ronycost.pdf', 'I');
?>