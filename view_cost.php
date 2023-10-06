<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT  * FROM costs where status= 1");
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');
?>
<style>
	.on-print{
		display: none;
	}
</style>
<noscript>
	<style>
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}
		table{
			width: 100%;
			border-collapse: collapse
		}
		tr,td,th{
			border:1px solid black;
			text-align: center;
		}
	</style>
</noscript>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">				
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
							 <p><center>For the Month of <b><?php echo date('F ,Y',strtotime($month_of.'-1')) ?></b></center></p>
						</div>
						<div class="row">
						   	<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center">#</th>

									<th class="">Electricity</th>
									<th class="">Gass</th>
									<th class="">Water</th>
									<th class="">Gard</th>
									<th class="">Other</th>
									<th class="">Remark</th>
									<th class="">Total Cost</th>
									<th class="">Total House Rent</th>
									<th class="">Rony</th>
									<th class="">Rupon</th>
								</tr>
							</thead>
								<tbody>	
								<?php								
								$payment = $conn->query("SELECT sum(amount) as paid FROM payments 
									where date_format(date_created,'%Y-%m') = '$month_of' order by unix_timestamp(date_created)  asc");
								$fullmonth =   $payment->num_rows > 0 ? $payment->
										fetch_array()['paid'] : 0;				
								?>
								<?php 
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
									<tr>
										<td><?php
										$i=1;
										echo $i++; ?></td>	
										<td><?php echo number_format ($electricity); ?></td>
										<td><?php echo number_format ($gas); ?></td>
										<td><?php echo number_format ($water);?></td>
										<td><?php echo number_format ($parent); ?></td>
										<td><?php echo number_format ($other); ?></td>
										<td><textarea class="form-control" rows="auto" cols="20">
											<?php echo trim(preg_replace('/\s+/', ' ', $description)); ?></textarea> </td>
										<td><?php echo number_format ($total_amount); ?></td>
											
										<td class=""><?php echo number_format($fullmonth); ?>
										</td>
										<td class=""><?php echo number_format($val,2); ?>
										</td>	
										<td class=""><?php echo number_format($val,2); ?>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- report -->
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("","_blank","width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
	$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=payment_report&'+$(this).serialize()
	})
</script>