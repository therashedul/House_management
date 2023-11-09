<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
					return 1;
			}else{
				return 3;
			}
	}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}
			return 1;
		}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_house(){
		extract($_POST);
		$data = " house_no = '$house_no' ";		
		$data .= ", category_id = '$category_id' ";
		$data .= ", description = '$description' ";
		$chk = $this->db->query("SELECT * FROM houses where house_no = '$house_no' ")->num_rows;
		if(empty($id)){				
			$save = $this->db->query("INSERT INTO houses set $data");
		}else{
			$save = $this->db->query("UPDATE houses set $data where id = $id");
		}	
		if($save)
			return 1;
	}
	function delete_house(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM houses where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_tenant(){
		extract($_POST);
		$data = " fullname = '$fullname' ";
		$data .= ", nid = '$nid' ";
		$data .= ", rent = '$rent' ";
		$data .= ", fmember = '$fmember' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", house_id = '$house_id' ";
		$data .= ", house_no = '$house_no' ";
		$data .= ", date_in = '$date_in' ";
			if(empty($id)){				
				$save = $this->db->query("INSERT INTO tenants set $data");
			}else{
				$save = $this->db->query("UPDATE tenants set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM tenants where id = ".$id);
		// $delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_slip(){
	   extract($_POST);
	   $data =array();     
	   // $total_unit = ($total_unit*7.2);  
              
       if($advance || $due_bill ){
           $total_bill = ($total_unit*7.2+$house_rent+$gas+$water+$dast)+$advance+$due_bill;
       }
       else{
           $total_bill = ($total_unit*7.2+$house_rent+$gas+$water+$dast);
       }

		$data = " house_rent = '$house_rent' ";
		$data .= ", first_unit = '$first_unit' ";
		$data .= ", last_unit = '$last_unit' ";
		if($last_unit){
			$total_unit= $last_unit-$first_unit;
		}
		$data .= ", total_unit = '$total_unit'";
		// $data .= ", total_unit = '$total_unit'";
		$data .= ", house_id = '$house_id' ";
		$data .= ", tenant_id = '$tenant_id' ";
		$data .= ", invoice = '$invoice' ";
		$data .= ", gas = '$gas' ";
		$data .= ", water = '$water' ";
		$data .= ", dast = '$dast' ";	
		$data .= ", advance = '$advance' ";
		$data .= ", total_bill = '$total_bill' ";
		$data .= ", due_bill = '$due_bill' ";
		$data .= ", date_in = '$date_in' ";

		
			if(empty($id)){				
				$save = $this->db->query("INSERT INTO slipes set $data");
			}else{
				$save = $this->db->query("UPDATE slipes set $data where id = $id");
			}
		if($save){

			return 1;
		}
	}
	function delete_slip(){
		extract($_POST);
		$delete = $this->db->query("UPDATE slipes set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_cost(){
	   extract($_POST);

	//    ===========================
		// $payable = abs($total_bill * $months);
		// $data['payable'] = number_format($payable,2);

		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments ");

		// $last_payment = $this->db->query("SELECT * FROM payments where  tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
	// ========================
	   $data =array();
	    $total_amount = ($electricity+$parent+$gas+$water+$other);
		$ronypart = ($paid - $total_amount) / 2;
     	$data = " electricity = '$electricity' ";
		$data .= ", gas = '$gas' ";
		$data .= ", water = '$water' ";
		$data .= ", parent = '$parent' ";
		$data .= ", other = '$other' ";	
		$data .= ", total_amount = '$total_amount' ";	
		$data .= ", rony_part = '$ronypart' ";	
		$data .= ", description = '$description' ";
		$data .= ", created = '$created' ";
			if(empty($id)){				
				$save = $this->db->query("INSERT INTO costs set $data");
			}else{
				$save = $this->db->query("UPDATE costs set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_cost(){
		extract($_POST);
		$delete = $this->db->query("UPDATE costs set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_rony(){
		extract($_POST);
		$data =array();
		$total_cost = ($electricity+$gass+$water+$other);
		$add_amount = ($rony_part+$house_rent);
		$rest_amount = ($add_amount-$total_cost);

		$data = " electricity = '$electricity' ";
		$data .= ", gass = '$gass' ";
		$data .= ", water = '$water' ";
		$data .= ", other = '$other' ";	
		$data .= ", rapartmant = '$rapartmant' ";
		$data .= ", total_cost = '$total_cost' ";	
		$data .= ", house_rent = '$house_rent' ";	
		$data .= ", rony_part = '$rony_part' ";			
		$data .= ", description = '$description' ";
		$data .= ", rest_amount = '$rest_amount' ";

		
		if(empty($id)){				
			$save = $this->db->query("INSERT INTO rony_houses set $data");
		}else{
			$save = $this->db->query("UPDATE rony_houses set $data where id = $id");
		}
		if($save)
			return 1;
	}
	function delete_rony(){
		extract($_POST);
		$delete = $this->db->query("UPDATE rony_houses set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_tdetails(){
		extract($_POST);
		$data = array();
		$month_of = date('Y-m');

		$tenants = $this->db->query("SELECT  t.*, t.fullname, s.total_bill, s.invoice FROM tenants t  INNER JOIN slipes s on t.id = s.tenant_id  where t.id = {$id} order by s.date_in desc");	


			// $tenants = $this->db->query("SELECT  t.*, t.fullname, s.total_bill, s.invoice FROM tenants t  INNER JOIN slipes s on t.id = s.tenant_id  where t.id = {$id} order by s.date_in desc limit 1");

		foreach($tenants->fetch_array() as $k => $v){
			if(!is_numeric($k)){
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$data['months'] = $months;

		$payable = abs($total_bill * $months);
		$data['payable'] = number_format($payable,2);
		
		$paids = $this->db->query("SELECT amount as paied FROM payments where activet = 1 and  date_format(date_created,'%Y-%m') = '$month_of' and   tenant_id =".$id."  order by unix_timestamp(date_created) desc");		


		// $paids = $this->db->query("SELECT amount as paied FROM payments where activet = 1 and   tenant_id =".$id." order by unix_timestamp(date_created) desc");

		
		$last_payment = $this->db->query("SELECT * FROM payments where  tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		
		$paid = $paids->num_rows > 0 ? $paids->fetch_array()['paied'] : 0;


		$data['fullname'] = ucwords($fullname);
		$data['house_no'] = number_format($house_no);
		$data['total_bill'] = number_format($total_bill);
		$data['invoice'] = $invoice;

		$data['paied'] = number_format($paid,2);

		$data['due_bill'] = number_format($total_bill-$paid,2);

		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';

		$data['outstanding'] = number_format($payable-$paid,2);
		
		$data['rent_started'] = date('M d, Y',strtotime($date_in));

		return json_encode($data);
	}
	
	function save_payment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set $data");
			$id=$this->db->insert_id;
		}else{
			$save = $this->db->query("UPDATE payments set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}
}