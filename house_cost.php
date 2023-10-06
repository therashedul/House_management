<?php include('db_connect.php');?>

<div class="container-fluid">
	<div class="row">
		<!-- FORM Panel -->

		<!-- Table Panel -->
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>List of Slip</b>
					<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_slip">
						<i class="fa fa-plus"></i> New Slip
					</a></span>
				</div>
				<div class="card-body">					
					<table class="table table-condensed table-bordered table-hover" id="example">
						<thead>
							<tr>
								<th class="text-center" scope="col">#</th>
								<th class="">Gas</th>
								<th class="">Electricity</th>
								<th class="">Water</th>
								<th class="">Parent Cost</th>
								<th class="">Other</th>
								<th class="text-center">Description</th>						
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$slip = $conn->query("SELECT  s.*,  t.fullname, t.house_no FROM slipes s INNER JOIN tenants t on s.tenant_id = t.id  where s.status = 1 ORDER by t.house_no desc");
							while($row=$slip->fetch_assoc()):
								$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
								$months = floor(($months) / (30*60*60*24));
								$payable = $row['total_bill'] * $months;
								$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
								$last_payment = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
								$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
								$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
								$outstanding = $payable - $paid;
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo ucwords($row['fullname']) ?>
									</td>
									<td>
										<?php echo $row['house_no']; ?>
									</td>
									<td>
										<?php echo $row['invoice']; ?>
									</td>
									<td>
										<p><?php echo $row['first_unit'] ?></p>
									</td>
									<td>
										<p><?php echo $row['last_unit'] ?></p>
									</td>																
									<td>
										<p><?php echo $row['date_in'] ?></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary view_slip" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye"></i></button>
										<button class="btn btn-sm btn-outline-primary edit_slip" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-pencil-alt"></i></button>
										<button class="btn btn-sm btn-outline-danger delete_slip" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Table Panel -->
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
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>


	$(document).ready(function() {
		var table = $('#example').DataTable( {
			lengthChange: false,
			buttons: [ 'copy', 'excel', 'pdf', 'print' ]
		} );
		
		table.buttons().container()
		.appendTo( '#example_wrapper .col-md-6:eq(0)' );
	} );


	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_slip').click(function(){
		uni_modal("New Slip","manage_slip.php","mid-large")
		
	})

	$('.view_slip').click(function(){
		uni_modal("View Slip","view_slip.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_slip').click(function(){
		uni_modal("Manage Slip Details","manage_slip.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_slip').click(function(){
		_conf("Are you sure to delete this Slip?","delete_slip",[$(this).attr('data-id')])
	})
	
	function delete_slip($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_slip',
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
</script>