<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM rony_houses where id= ".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k=$val;
	}
	// print_r($qry);
}
?>


<div class="container-fluid">
	<form action="" id="manageRony">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<?php
					$slip = $conn->query("SELECT rony_part FROM costs where status = 1 ORDER by id DESC limit 1 ;");
					while($row = $slip->fetch_assoc()):
						?>
						<input type="hidden"  value="<?php  $rony_part = $row['rony_part']; 									
									if(!is_null($rony_part) && isset($rony_part) ? $rony_part : "0"){
										print($rony_part);
									}else{
										print("0");
									}
									?>">
			<?php endwhile; ?>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Apartmant</label>
				<input type="text" class="form-control" name="rapartmant"  value="<?php echo isset($rapartmant) ? $rapartmant :'301+302' ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Rony amount</label>
				<input type="text" class="form-control" name="rony_part"  id="rony" value="<?php echo isset($rony_part) ? $rony_part : $row['rony_part']; ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">House Rent	</label>
				<input type="text" class="form-control" name="house_rent"  value="<?php echo isset($house_rent) ? $house_rent : '0' ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Electricity Unit</label>
				<input type="text" class="form-control" name="electricity"  value="<?php echo isset($electricity) ? $electricity :'0' ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Gass</label>
				<input type="text" class="form-control" name="gass"  value="<?php echo isset($gass) ? $gass :'0' ?>">
			</div>	
			<div class="col-md-4">
				<label for="" class="control-label">Water</label>
				<input type="text" class="form-control" name="water"  value="<?php echo isset($water) ? $water :'0' ?>">
			</div>		
			<div class="col-md-4">
				<label for="" class="control-label">Other</label>
				<input type="text" class="form-control" name="other"  value="<?php echo isset($other) ? $other :'0' ?>">
			</div>

			<div class="col-md-4">
				<label for="" class="control-label">Remark</label>
				<textarea name="description" class="form-control"><?php echo isset($description)?$description :''; ?></textarea>	

			</div>

		</div>
	</form>
</div>
<script>


	$('#manageRony').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_rony',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
</script>