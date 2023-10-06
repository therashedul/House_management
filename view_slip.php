<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT  s.*,  t.fullname,t.house_no FROM slipes s INNER JOIN tenants t on s.tenant_id = t.id where s.id= ".$_GET['id']);
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
							<h3><b><center>Nadir Villa</center></b></h3>
							<p><center>For the Month of <b><?php echo date('F ,Y',strtotime($month_of.'-1')) ?></b></center></p>
						</div>
						<div class="row justify-content-center">
    					   <div class="col-auto">
    					   	<table class="table table-bordered table-responsive">
    					   		<thead>
    					   			    <tr>
											<th style="width: auto; text-align: left; padding-left: 10px;">Name</th>
											<th style="width: auto; text-align: left; padding-left: 10px;">Apartment</th>
											<th style="width: auto; text-align: left; padding-left: 10px;" >Invoice</th>										
											
									    </tr>
										<tr>
											<td style="width:auto; text-align: left; padding-left: 10px;"><?php echo ucwords($fullname); ?></td>
											<td style="width: auto; text-align: left; padding-left: 10px;"><?php echo $house_no; ?></td>
											<td style="width:auto; text-align: left; padding-left: 10px;"><?php echo ucwords($invoice); ?></td>
										</tr>
    					   		</thead>
    					   	</table>
    					   	<div style="margin-bottom: 20px;"></div>
						   	<table class="table table-bordered table-responsive" >
								<thead>
										
								</thead>
								<tbody>									
										<tr> <th style="width: auto; text-align: left; padding-left: 10px;">House Rent</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo number_format($house_rent); ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Gass</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo $gas; ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Water</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo $water;?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Electricity</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo number_format($total_unit*7.2); ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Other</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo $dast; ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;"> Due </th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo number_format($due_bill); ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Advance</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><?php echo number_format($advance); ?>  </td></tr>
										<tr><th style="width: auto; text-align: left; padding-left: 10px;">Total Amount</th>
										<td style="width: 80%; text-align: left; padding-left: 10px;"><b><?php echo number_format($total_bill,2) ?>  </b></td>
									</tr>															
								</tbody>								
							</table>
							<div style="margin-top: 20px;"></div>
							<table  class="table borderless table-responsive" >
								<tfoot>								
			                      <th align="center"><p style="text-align: center;  display: block;">[ Note:Please pay your rent before the date 13, otherwise, will be adding a delay fined 400/= Taka]</p></th>
								
								</tfoot>
							</table>
						</div>
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
