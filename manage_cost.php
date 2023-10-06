<?php 
	include 'db_connect.php'; 
	if(isset($_GET['id'])){
		$qry = $conn->query("SELECT * FROM costs where id= ".$_GET['id']);
		foreach($qry->fetch_array() as $k => $val){
			$$k=$val;
		}
	}
?>
   <?php
        $tamount = 0;
        $month_of = date('Y-m');
        $payment = $conn->query("SELECT sum(amount) as paid FROM payments where date_format(date_created,'%Y-%m') = '$month_of' order by unix_timestamp(date_created)  asc");
        while ($row = $payment->fetch_assoc()) {  
            echo $row['paid'] > 0 ? number_format($row['paid'], 2) : 0 ;
        }                                       
            // $payment = $conn->query("SELECT sum(amount) as paid FROM payments where date(date_created) = '" . date('Y-m-d') . "' ");
            // echo $payment->num_rows > 0 ? number_format($payment->fetch_array()['paid'], 2) : 0;
    ?>
<div class="container-fluid">
	<form action="" id="manage-cost">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row form-group">
				    <div class="col-md-4">
						<label for="" class="control-label">Electricity	</label>
						<input type="text" class="form-control" name="electricity"  value="<?php echo isset($electricity) ? $electricity :'' ?>">
					</div>
					<div class="col-md-4">
						<label for="" class="control-label">Gas</label>
						<input type="text" class="form-control" name="gas"  value="<?php echo isset($gas) ? $gas :'' ?>">
					</div>
					<div class="col-md-4">
						<label for="" class="control-label">Water</label>
						<input type="text" class="form-control" name="water"  value="<?php echo isset($water) ? $water :'' ?>">
					</div>
				</div>
				<div class="row form-group">	
				    <div class="col-md-4">
						<label for="" class="control-label">Gourd</label>
						<input type="text" class="form-control" name="parent"  value="<?php echo isset($parent) ? $parent :'' ?>">
					</div>				
					<div class="col-md-4">
						<label for="" class="control-label">Other</label>
						<input type="text" class="form-control" name="other"  value="<?php echo isset($other) ? $other :'' ?>">
					</div>
					<!-- <div class="col-md-4">
						<label for="" class="control-label">Rony</label>
						<input type="text" class="form-control" name="rony"  value="<?php echo isset($rony) ? $rony :'' ?>">
					</div> -->
					<div class="col-md-4">
						<label for="" class="control-label">Remark</label>
						<textarea name="description" class="form-control"><?php echo isset($description) ? $description :''; ?></textarea>	
					</div>
					<div class="col-md-4">
				<label for="" class="control-label">Date</label>
				<input type="date" class="form-control" name="created"  value="<?php echo isset($created) ? date("Y-m-d",strtotime($created)) :'' ?>" required>
			</div>	
				</div>
			</form>
		</div>
		<script>
			$('#manage-cost').submit(function(e){
				e.preventDefault()
				start_load()
				$('#msg').html('')
				$.ajax({
					url:'ajax.php?action=save_cost',
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