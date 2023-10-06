<?php include('db_connect.php');?>
<?php $month_of = date('Y-m'); ?>

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
						<b>List of Payments</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_invoice">
							<i class="fa fa-plus"></i> New Entry
						</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Date</th>
									<th class="">Tenant</th>
									<th class="">Apratment</th>
									<th class="">Invoice</th>
									<th class="">Amount</th>
									<th class="">Due</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$due_bill="0";
									// $invoices = $conn->query("SELECT p.*,t.fullname FROM payments p inner join tenants t on t.id = p.tenant_id where t.status = 1 order by date(p.date_created) desc ");
								$invoices = $conn->query("SELECT p.*,concat(t.fullname) as name,h.total_bill,t.house_no FROM payments p inner join tenants t on t.id = p.tenant_id inner join slipes h on h.tenant_id = t.id where date_format(p.date_created,'%Y-%m') = '$month_of' AND  t.status = 1 order by unix_timestamp(date_created)  asc");

								while($row=$invoices->fetch_assoc()):
									$due_bill = $row['amount']-$row['total_bill'];
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
										</td>
										<td class=""><?php echo ucwords($row['name']) ?>
										</td>
										<td class=""><?php echo ucwords($row['house_no']) ?>
										</td>
										<td class=""><?php echo ucwords($row['invoice']) ?>
										</td>
										<td class="text-right"><?php echo number_format($row['amount'],2) ?>
										</td>
										<td class="text-right"><?php echo number_format($due_bill,2) ?>
										</td>								
										<td class="text-center">
											<button class="btn btn-sm btn-outline-primary edit_invoice" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-pencil-alt"></i></button>
											<button class="btn btn-sm btn-outline-danger delete_invoice" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
		max-height:150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})

	$('#new_invoice').click(function(){
		uni_modal("New invoice","manage_payment.php","mid-large")

	})
	$('.edit_invoice').click(function(){
		uni_modal("Manage invoice Details","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")

	})
	$('.delete_invoice').click(function(){
		_conf("Are you sure to delete this invoice?","delete_invoice",[$(this).attr('data-id')])
	})

	function delete_invoice($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payment',
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