<?php include('db_connect.php');?>
<?php $month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Cost</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_cost">
							<i class="fa fa-plus"></i> New Slip
						</a></span>				
						<span class="float:left"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" target="__blank" href="cost_pdf.php?month_of=<?php echo ($month_of) ?>">
							<i class="fa fa-plus"></i> Pdf Slip
						</a></span>
					</div>
					<div class="card-body">
						<form id="filterCost">
							<div class="row form-group">
								<label class="control-label col-md-2 offset-md-2 text-right">Month of: </label>
								<input type="month" name="month_of" class='from-control col-md-4' value="<?php echo ($month_of) ?>">
								<button class="btn btn-sm btn-block btn-primary col-md-2 ml-1">Filter</button>
							</div>
						</form>
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Electricity</th>
									<th class="">Gas</th>
									<th class="">Water</th>
									<th class="">Gard</th>
									<th class="">Other</th>
									<th class="">Remark</th>
									<th class="">Total Cost</th>
									<th class="">Total Amount</th>
									<th class="">Rony</th>
									<th class="">Rupon</th>
									<th class="">Date</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php								
								$payment = $conn->query("SELECT sum(amount) as paid FROM payments where date_format(date_created,'%Y-%m') = '$month_of' order by unix_timestamp(date_created)  asc");
								$fullmonth =   $payment->num_rows > 0 ? $payment->
								fetch_array()['paid'] : 0;				
								?>
								<?php 
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
										$val = ($fullmonth-$row['total_amount'])/2;
										?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td>
												<?php echo number_format( $row['electricity']); ?>
											</td>
											<td>
												<?php echo number_format($row['gas']); ?>
												
											</td>
											<td>
												<?php echo number_format( $row['water']); ?>
											</td>
											<td class="">
												<p><?php echo number_format($row['parent']) ?></p>
											</td>									
											<td class="">
												<p><?php echo number_format($row['other']); ?></p>
											</td>	
											<td class="">
												<p><?php echo $row['description'] ?></p>
											</td>
											<td class=""><?php echo number_format($row['total_amount']); ?></td>	
											<td class=""><?php echo number_format($fullmonth); ?>
										</td>
										<td class="">
											<?php echo number_format($val,2); ?></td>	
											<td class=""><?php echo number_format($val,2); ?></td>									
											<td class="">
												<?php echo date('M d, Y',strtotime($row['created'])) ?>

											</td>
											<td class="text-center">
												<button class="btn btn-sm btn-outline-primary view_cost" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-eye"></i></button>
												<button class="btn btn-sm btn-outline-primary edit_cost" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-pencil-alt"></i></button>
												<button class="btn btn-sm btn-outline-danger delete_cost" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
											</td>
										</tr>
									<?php endwhile; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	
</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width: 100px;
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_cost').click(function(){
		uni_modal("New Cost","manage_cost.php","mid-large")
		
	})

	$('.view_cost').click(function(){
		uni_modal("View cost","view_cost.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_cost').click(function(){
		uni_modal("Manage Cost Details","manage_cost.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_cost').click(function(){
		_conf("Are you sure to delete this Slip?","delete_cost",[$(this).attr('data-id')])
	})
	
	function delete_cost($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_cost',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
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
	$('#filterCost').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=cost&'+$(this).serialize()
	})
</script>