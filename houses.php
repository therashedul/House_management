<?php include('db_connect.php');?>

<div class="container-fluid">	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
		<!-- Table Panel -->
		<div class="col-md-12">
				<div class="card">
				<div class="card-header">
						<b>List of Apartmant</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_house">
					<i class="fa fa-plus"></i> New Apartmant
				        </a></span>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Apartmant No</th>
									<th class="text-center">Apartmant Type</th>
									<th class="text-center">Description</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$house = $conn->query("SELECT h.*,c.name as cname FROM houses h inner join categories c on c.id = h.category_id order by id asc");
								while($row=$house->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<?php echo $row['house_no'] ?>
									</td>
									<td class="">
										<?php echo $row['cname'] ?>
									</td>
									<td class="">
									<?php echo $row['description'] ?>										
									</td>
									<td class="text-center">									
										<button class="btn btn-sm btn-outline-primary edit_house" type="button" data-id="<?php echo $row['id'] ?>" ><i class="fas fa-pencil-alt"></i></button>
										<button class="btn btn-sm btn-outline-danger delete_house" type="button" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_house').click(function(){
		uni_modal("New Apartmant","manage_house.php","mid-large")
		
	})
	$('.edit_house').click(function(){
		uni_modal("Manage House Details","manage_house.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_house').click(function(){
		_conf("Are you sure to delete this apartmant?","delete_house",[$(this).attr('data-id')])
	})
	
	function delete_house($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_house',
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