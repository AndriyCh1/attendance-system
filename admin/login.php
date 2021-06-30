<?php 

	include('connection.php');
	session_start();

	if(isset($_SESSION["adminId"])){
		header('location:index.php');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
    <link rel="stylesheet" href="../css/login.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

</head>
<form method="post" class="login-form">
	<h1>Вхід</h1>
	<div class="input-form">
		<input name="admin_name" class="admin-name" type="text" placeholder="Логін">
		<span class="error-admin-name"></span>
		<input name="admin_password" class="password" type="password" placeholder="Пароль">
		<span class="error-admin-password"></span>
		<input name="adminLogin" class="login-button" type="submit" value="Увійти">
	</div>
</form>
</body>
</html>

<script>
	$(document).ready(function(){
		$('.login-form').on('submit',function(event) {
			event.preventDefault();
			$.ajax({
				url:"check-admin-login.php",
				method: "POST",
				data:$(this).serialize(),
				dataType:"json",
				success:function(data){
					if(data.success){
						location.href = "<?php echo $baseUrl;?>index.php";
					}
					if(data.error){
						if(data.error_admin_name != ''){
							$('.error-admin-name').text(data.error_admin_name);
						}else{
							$('.error-admin-name').text('');
						}

						if(data.error_admin_password != ''){
							$('.error-admin-password').text(data.error_admin_password);
						}
						else{
							$('.error-admin-password').text('');
						}
					}
				}
			})
		})
	});
</script>