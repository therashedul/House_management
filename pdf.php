<?php include('db_connect.php');?>
<?php $month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m'); ?>
<?php
require('fpdf/fpdf.php');
// require ("WriteHTML.php"); 



class PDF extends FPDF
{
// Page header
	function Header()
	{
    // Logo
		// $this->Image('pic.png',10,7,70);
		$this->SetFont('Arial','B',20);
    // Move to the right
		$this->Cell(80);
    // Title
    //$this->Cell(80,10,'Students List',1,0,'C');
    // Line break
		$this->Ln(20);
		$this->Cell(65);
		$this->Cell(60,10,'Our House Cost',1,0,false);
		$this->Ln(10);
	}

// Page footer
	function Footer()
	{
    // Position at 1.5 cm from bottom
		$this->SetY(-15);
    // Arial italic 8
		$this->SetFont('Arial','I',8);
    // Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

	// Position at 2.0 cm from bottom
		$this->SetY(-20);
    // Arial italic 8
		$this->SetFont('Arial','B','I',8);
    // Page number
		$this->Cell(0,10,'https://webxpartbd.com',0,0,'C');
	}
}
$pdf = new PDF(); 
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',16);
$pdf->Ln();
?>


<?php 

$i = 1;

$payment = $conn->query("SELECT sum(amount) as paid FROM payments 
	where date_format(date_created,'%Y-%m') = '$month_of' order by unix_timestamp(date_created)  asc");
$fullmonth =   $payment->num_rows > 0 ? $payment->
fetch_array()['paid'] : 0;				
?>
<?php 
if(isset($fullmonth) ):
	$i = 1;
	$slip = $conn->query("SELECT  * FROM costs where status = 1 ORDER by id desc");

	while($row=$slip->fetch_assoc()):
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['created']." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$payable = $row['total_amount'] * $months;
		$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
		$last_payment = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$outstanding = $payable - $paid;								
		$val = ($fullmonth-$row['total_amount'])/2;
		?>
		<?php
// $pdf->Cell(40,5,' ','LTR',0,'L',0);   // empty cell with left,top, and right borders
// $pdf->Cell(50,5,'Words Here',1,0,'L',0);
// $pdf->Cell(50,5,'Words Here',1,0,'L',0);
// $pdf->Cell(20,5,'Words Here',1,0,'L',0);
// $pdf->Cell(20,5,'Words Here',1,0,'L',0);
// $pdf->Cell(40,5,'Words Here','LR',1,'C',0);  // cell with left and right borders
// $pdf->Cell(50,5,'[ x ] abc',1,0,'L',0);
// $pdf->Cell(50,5,'[ x ] checkbox1',1,0,'L',0);
// $pdf->Cell(40,5,'','LBR',1,'L',0);   // empty cell with left,bottom, and right borders
// $pdf->Cell(50,5,'[ x ] def',1,0,'L',0);
// $pdf->Cell(50,5,'[ x ] checkbox2',1,0,'L',0);
		?>

		<?php
		$width_cell=array(10,20,30,40,50);
		$height_cell=array(10,20,30,40,50);
		$td_name = ['Electricity','Gass', 'Water'];

		$pdf->SetFillColor(193,229,252);
		$pdf->SetFont('Arial','B','C',12);
		$pdf->Cell($width_cell[2],$height_cell[0],$td_name[0],true);
		$pdf->Cell($width_cell[1],10,$td_name[1],1,0,true); 
		$pdf->Cell($width_cell[1],10,$td_name[2],1,0,true); 
		$pdf->Cell($width_cell[1],10,'Gard',1,0,true); 
		$pdf->Cell($width_cell[1],10,'Other',1,0,true); 
		$pdf->Cell($width_cell[2],10,'Total Cost',1,0,true); 
		$pdf->Cell($width_cell[3],10,'Total Amount',1,0,true); 
		$pdf->Ln();
		$pdf->SetFont('Arial','',14);
		$pdf->Cell($width_cell[2],10,number_format($row['electricity']),1,0,false);
		$pdf->Cell($width_cell[1],10,number_format($row['gas']),1,0,false);
		$pdf->Cell($width_cell[1],10,number_format($row['water']),1,0,false);
		$pdf->Cell($width_cell[1],10,number_format($row['parent']),1,0,false);
		$pdf->Cell($width_cell[1],10,number_format($row['other']),1,0,false); 
		$pdf->Cell($width_cell[2],10,number_format($row['total_amount']),1,0,false); 
		$pdf->Cell($width_cell[3],10,number_format($fullmonth),1,0,false);  
		$pdf->Ln(20);
		$pdf->Cell(45);
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell($width_cell[4],10,'Rony',1,0,'C',true); 
		$pdf->Cell($width_cell[4],10,'Rupon',1,0,'C',true); 
		$pdf->Ln();
		$pdf->Cell(45);
		$pdf->SetFont('Arial','',14);
		$pdf->Cell($width_cell[4],10,number_format($val,2),1,0,'R',false);
		$pdf->Cell($width_cell[4],10,number_format($val,2),1,0,'R',false);
		?>
	<?php endwhile; ?>
<?php endif; ?>
<?php
$pdf->Output('house_cost.pdf','I'); // Send to browser and display
?>






