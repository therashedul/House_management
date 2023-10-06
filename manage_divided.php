<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM slipes where id= ".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k=$val;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-slip">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Apartment No</label>
				<select name="house_id" id="products" class="custom-select select2">
					<option value=""></option>
					<?php 
					$slip = $conn->query("SELECT * from tenants where status = 1"
						.(isset($house_id)? " or id = $house_id": "" )." ");
					while($row= $slip->fetch_assoc()):
						?>
						<option value="<?php echo $row['id'] ?>" 
							<?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>  data-price="<?php echo $row['id']; ?>" data-rent="<?php echo $row['rent']; ?>" >
							<?php echo $row['house_no'] ?>							
						</option>
						<?php $value = $row['rent'];?>
					<?php endwhile; ?>
				</select>	
				<input class="form-control" type="hidden" value="<?php echo isset($tenant_id) ? $tenant_id :'' ?>" name="tenant_id" id="priceInput"  ></br>		
			</div>

			
			<div class="col-md-4">
				<label for="" class="control-label">Date</label>
				<input type="date" class="form-control" name="date_in"  value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
			</div>	

			<div class="col-md-4">
				<?php 
				date_default_timezone_set("Asia/Dhaka");
				$invoice_number = date('sh-md'); ?>
				<label for="" class="control-label">Invoice:</label>
				<input type="text" class="form-control" name="invoice"  value="<?php echo isset($invoice) ? $invoice :$invoice_number ?>"  readonly="readonly">
			</div>			
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">First Unit</label>
				<input type="text" class="form-control" name="first_unit"  value="<?php echo isset($first_unit) ? $first_unit :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Last Unit</label>
				<input type="text" class="form-control" name="last_unit"  value="<?php echo isset($last_unit) ? $last_unit :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">House Rent	</label>
				<input type="text" class="form-control" value="<?php echo isset($house_rent) ? $house_rent :'' ?>" name="house_rent"  id="rent" readonly="readonly" ></br>		
			</div>			
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Gas</label>
				<input type="text" class="form-control" name="gas"  value="<?php echo isset($gas) ? $gas :'975' ?>" readonly = "readonly" >
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Water</label>
				<input type="text" class="form-control" name="water"  value="<?php echo isset($water) ? $water :'800' ?>"  readonly = "readonly" >
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Other</label>
				<input type="text" class="form-control" name="dast"  value="<?php echo isset($dast) ? $dast :'100' ?>"  readonly = "readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label" style="color:red; margin-top: 10px;">Advance</label>
				<input type="text" class="form-control" name="advance"  value="<?php echo isset($advance) ? $advance :'' ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label" style="color:red; margin-top: 10px;">Due Bill</label>
				<input type="text" class="form-control" name="due_bill"  value="<?php echo isset($due_bill) ? $due_bill :'' ?>">
			</div>
		</div>
	</form>
</div>
<script>
	$(function () {
		$('#products').change(function () {
			$('#priceInput').val($('#products option:selected').attr('data-price'));
			$('#rent').val($('#products option:selected').attr('data-rent'));
		});
	});
	$('#manage-slip').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_slip',
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