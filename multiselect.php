<?php
	function generateRandomCode($length = 6) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomCode = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomCode .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomCode;
	}
	// 랜덤 코드 생성 및 출력
	$code = generateRandomCode();
?>
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/css/bootstrap-multiselect.min.css" rel="stylesheet">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/js/bootstrap-multiselect.min.js"></script>

<!DOCTYPE html>
<html>
<body>
<div class = "e_body">
	<div class = "e_mid">
		<div class = "body_table">
			<table class = "tbl_score" id = "edu_table">
				<?
					$SQL = "SELECT  user_id, user_name  FROM table WHERE user_use = 1 order by user_group desc";
					$sql_query = mysqli_query($db_link, $SQL);
					while($row = mysqli_fetch_array($sql_query)) {
						$user_id = $row["user_id"];
						$user_name = $row["user_name"];
						$tbody_html_user .=  "<option value=\"$user_id\">$user_name</option>";
					}
				?>
				<tr id = "selectpep">
					<th>
						<span class = "re_td2"><span class = "re_bold">대상인원</span></span>
					</th>
					<td>
            					<input type = "hidden" name = "edu_id" value = "<?php echo htmlspecialchars($code); ?>" class = "input_title">
						<div class = "selectpeople">
							<select id="chkveg" multiple="multiple">
								<?= $tbody_html_user?>
							</select>
							<input type="button" id="btnget" value="확인"/>
						</div>
						<div id="selectedValues"></div>
					</td>
				</tr>
			</table>
		</div>
		<div class = "golist">
			<button type = "button" class = "listbtn" onclick = "goSubmit()">등록</button>
			<a href = "./edu_manage.php" style = "color:white;"><button type = "button" class = "listbtn">목록</button></a>
		</div>
	</div>
</div>
</body>
</html>
<? include "./foot.php";?>
<script>
$(document).ready(function() {
	$('.multiselect-selected-text').text('대상자 선택'); // span 내용 변경
	});
  
	$('#chkveg').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterBehavior: 'text', //text값으로 검색
		//filterBehavior: 'value' -> value값으로 검색
	
		filterFunction: function(element, query) {
			var value = $(element).text().toLowerCase();
			query = query.toLowerCase();
			return value.indexOf(query) >= 0;
		}
	});
  
  	$('#btnget').click(function() {
  		var selectedTexts = [];
  		$("#chkveg option:selected").each(function() {
  			selectedTexts.push($(this).text());
  		});
  		var selectedValues = selectedTexts.join(', ');
  		$('#selectedValues').text(selectedValues);
  	});
  
	function goSubmit() {
		isSubmitClick = true;
		var edu_id = $("input[name='edu_id']").val();
		var fnc = "education_add";
		var userNames = [];
		var userNameInputs = $('#chkveg').val()
		var tot_user_name = userNameInputs.toString();
		
		for (var i = 0; i < userNameInputs.length; i++) {
			if (userNameInputs[i].value !== '') {
				var user_id = userNameInputs[i];
				var completedRequests = 0;
				
				$.ajax({
					type: 'POST',
					url: 'multiselect_form.php',
					data: {
					"function" : fnc,
					"tot_user_name" : tot_user_name,
					"edu_id" : edu_id,
					"user_id" : user_id
				},
				dataType: 'json',
				success: function(response) {
					console.log(response.status);
					if (response.status == 'success') {
						completedRequests++;
						if (completedRequests === userNameInputs.length) {
							alert('모든 업데이트가 완료되었습니다.');
							window.location.href = './your_page_url.php';
						}
					} else {
						alert('오류가 발생했습니다: ' + response.msg);
						console.log('Error: ' + response.msg);
					}
				},
				error: function(xhr, status, error) {
					alert('AJAX 오류입니다. 관리자에게 문의 바랍니다.');
					console.log('AJAX Error: ' + status + ' - ' + error);
				}
				});
			}
		}
	}
</script>
<style>
	.e_body{margin:40px;margin-bottom:100px;}
	table{width:100%;}
	table, td, th {border-bottom:1px solid #c0bebe; border-top:1px solid black; border-collapse:collapse; font-size:18px;}
	th, td{height:50px;}
	th{width:25%;}
	.golist {margin-top:130px; text-align:center;}
	.listbtn, #btnget {background-color:#214796; height:40px; color:white; border:none; border-radius:8px; padding-right:20px; padding-left:20px;font-size:16px; font-weight:600;}
	.re_bold {font-weight:bold;}
	.re_td2 {margin-left:20px; margin-right:20px;}
	.multiselect-container>li>a>label {padding: 4px 20px 3px 20px;}
	.multiselect-container{height:250px; overflow:scroll;font-size:11px;}
	.multiselect.dropdown-toggle.custom-select.text-center{background-color:#214796; border:none;}
	.multiselect-search.form-control{font-size:11px;}
</style>
