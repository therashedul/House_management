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
						<span class="float:right">
							<a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_cost">
							<i class="fa fa-plus"></i> New Cost
						</a></span>					
						<span class="float:right">
							<a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="rony_pdf_file.php?month_of=<?php echo ($month_of) ?> " target="__blank" id="pdf_file">
							<i class="fa fa-plus"></i>PDF File
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
									<th class="">Apratmant</th>
									<th class="">House Rent</th>
									<th class="">Electricity</th>
									<th class="">Gass</th>
									<th class="">Water</th>
									<th class="">Other</th>
									<th class="">Remark</th>
									<th class="">Total Cost</th>
									<th class="">Rony Part</th>
									<th class="">rest Amount</th>							
									<th class="">Date</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php	
								$i = 1;
								$rony = $conn->query("SELECT  * FROM rony_houses where status = 1  AND date_format(created,'%Y-%m') = '$month_of' ORDER by id desc");
								while($row=$rony->fetch_assoc()):
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<?php echo  $row['rapartmant']; ?>
										</td>
										<td>
											<?php echo number_format( $row['house_rent']); ?>
										</td>	
										<td>
											<?php echo number_format( $row['electricity']); ?>
										</td>
										<td>
											<?php echo number_format($row['gass']); ?>
										</td>
										<td class="">
											<p><?php echo number_format($row['water']) ?></p>
										</td>									
										<td class="">
											<p><?php echo number_format($row['other']); ?></p>
										</td>	
										<td class="">
											<p><?php echo $row['description'] ?></p>
										</td>
										<td class=""><?php echo number_format($row['total_cost']); ?>
										</td>	
										<td class=""><?php echo number_format($row['rony_part']); ?>
										</td>
										<td class="">
											<p> <b><?php echo number_format($row['rest_amount']); ?></b></p>
										</td>	

										<td class="">
											<p> <?php echo $row['created'] ?></p>
										</td>
										<td class="text-center">
										<!-- 	<button class="btn btn-sm btn-outline-primary view_rony" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-eye"></i></button> -->
											<button class="btn btn-sm btn-outline-primary edit_rony" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-pencil-alt"></i></button>
											<button class="btn btn-sm btn-outline-danger delete_rony" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
		max-height :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})

	$('#new_cost').click(function(){
		uni_modal("Rony Cost","manage_rony.php","mid-large")

	})

	$('.view_rony').click(function(){
		uni_modal("View cost","view_rony.php?id="+$(this).attr('data-id'),"large")

	})
	$('.edit_rony').click(function(){
		uni_modal("Manage Cost Details","manage_rony.php?id="+$(this).attr('data-id'),"mid-large")

	})
	$('.delete_rony').click(function(){
		_conf("Are you sure to delete this Slip?","delete_rony",[$(this).attr('data-id')])
	})

	function delete_rony($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_rony',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	}
	$('#filterCost').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=rony&'+$(this).serialize()
	})
</script>