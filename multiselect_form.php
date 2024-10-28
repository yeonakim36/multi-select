<?php
    include_once "./conndb.php";
  	header("Content-Type:application/json");
  
  	if (!function_exists('education_add')) { //개별
  		function education_add() { // Add the columns you want
  			$msg = "success";
  			$tot_user_name = $_POST['tot_user_name'];
  			$edu_id = $_POST['edu_id'];
  			$user_id = $_POST['user_id'];
  
  			$db_link = db_conn();
  
  			$sql0 = "SELECT user_name FROM user_table WHERE user_id LIKE '%$user_id%'";
  			$result = mysqli_query($db_link, $sql0);
  			if ($result) {
  				$row = mysqli_fetch_assoc($result);
  				$user_name = $row['user_name'];
  			} else {
  				$msg = "select_error";
  			}
  
  			$sql1 = "INSERT INTO edulist_table (user_id, edu_id) VALUES ('$user_id', '$edu_id');"; // Add the columns you want
  			$update_edu = mysqli_query($db_link, $sql1);
  
  			if(!$update_edu){
  				$msg = "update_error".mysqli_error($db_link);
  				mysqli_rollback($db_link);
  			} else {
  				$sql2 = "INSERT IGNORE INTO edu_table (edu_id) VALUES ('$edu_id');"; // Add the columns you want
  				$update_edu2 = mysqli_query($db_link, $sql2);
  	
  				if(!$update_edu2){
  					$msg = $sql2."update2_error: " . mysqli_error($db_link);
  					mysqli_rollback($db_link);
  				} else {
  					mysqli_commit($db_link);
  				}
  			}
  
  			$return_value = array();
  			if ($msg == "success") {
  				$return_value['status'] = 'success';
  			} else {
  				$return_value['status'] = 'error';
  			}
  			$return_value['msg'] = $msg;
  
  			echo json_encode($return_value, JSON_UNESCAPED_UNICODE);
  			return true;
  			}
  	}

	// 함수 호출 부분
	$function_name = '';
	if (isset($_POST['function']) && !empty($_POST['function'])) {
		$function_name = $_POST['function'];
	}
	
	if (function_exists($function_name)) {
		// 변수값으로 함수 호출
		if ($function_name()) {
			// 트랜잭션 완료
	//        $db->trans_commit();
		} else {
			// 트랜잭션 롤백
	//        $db->trans_rollback();
		}
	} else {
		// 함수가 존재 하지 않을 시 반환할 값
		echo json_encode(
			array(
				'function' => $function_name,
				'code' => -2,
				'msg' => '존재하지 않는 함수입니다.'
			)
		);
	}
?>
