
<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM houses where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>

<div class="container-fluid">
	<form action="" id="manage-house">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		                   <div class="form-group">
								<label class="control-label">Apartman No</label>
								<input type="text" class="form-control" name="house_no"  value="<?php echo isset($house_no) ? $house_no :'' ?>" required="">
						   </div>
						  	<div class="form-group">
								<label class="control-label">Select Apartman Type</label>
								<select name="category_id" id="" class="custom-select" required>
									<?php 
									$categories = $conn->query("SELECT * FROM categories order by name asc");
									if($categories->num_rows > 0):
									while($row= $categories->fetch_assoc()) :
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo isset($row['name']) ? $row['name'] :'' ?> <?php ?></option>
								<?php endwhile; ?>
								<?php else: ?>
									<option selected="" value="" >Please check the category list.</option>									
								<?php endif; ?>
								</select>
							</div>

							<div class="form-group">
								<label for="" class="control-label">Description</label>
								<textarea name="description"    cols="30" rows="4" class="form-control" required> <?php echo isset($description) ? $description :'' ?></textarea>
							</div>
							
					
		</div>
	</form>
</div>
<script>
	
	$('#manage-house').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_house',
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