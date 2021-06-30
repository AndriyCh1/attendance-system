<?php 

	include('connection.php');
	session_start();

	if(isset($_SESSION["worker_id"])){
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
    <link rel="stylesheet" href="css/login.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

</head>
<body>
<form method="post" class="login-form">
	<h1>Вхід</h1>
	<div class="input-form">
		<input name="worker_email" class="worker-email" type="email" placeholder="Електронна пошта">
		<span class="error-worker-email"></span>
		<input name="worker_password" class="password" type="password" placeholder="Пароль">
		<span class="error-worker-password"></span>
		<input name="worker_login" class="login-button" type="submit" value="Увійти">
	</div>
	<a href="registration.php" class="not-registred">Не зареєстровані?</a>
</form>
</body>
</html>

<script>
	$(document).ready(function(){
		$('.login-form').on('submit',function(event) {
			event.preventDefault();
			$.ajax({
				url:"check-worker-login.php",
				method: "POST",
				data:$(this).serialize(),
				dataType:"json",
				success:function(data){
					console.log(data);
					if(data.success){
						location.href = "<?php echo $baseUrl;?>index.php";
					}
					if(data.error){
						if(data.error_worker_name != ''){
							$('.error-worker-email').text(data.error_worker_email);
						}else{
							$('.error-worker-email').text('');
						}

						if(data.error_worker_password != ''){
							$('.error-worker-password').text(data.error_worker_password);
						}else{
							$('.error-worker-password').text('');
						}
					}
				}
			})
		})
	});
</script>


	
   
