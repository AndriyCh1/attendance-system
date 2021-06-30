<?php 
    session_start(); 
    $error = '';
    if(isset($_SESSION['message'])){
        $error = $_SESSION['message'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="css/registration.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<body>
    <form action="check-registration.php" method="post" class="registration" enctype="multipart/form-data">
        <h1>Реєстрація</h1>
        <div class="input-form">
            <input name="last_name" type="text" class="last-name" placeholder="Прізвище" required>
            <input name="first_name" type="text" class="first-name" placeholder="Ім'я" required>
            <input name="patronymic" type="text" class="patronymic" placeholder="По батькові" required>
            <input name="email" type="email" class="email" placeholder="Електронна пошта" required>
		    <input name="password" class="password" type="password" placeholder="Пароль" required>
		    <input name="password_confirm" class="repassword" type="password" placeholder="Повторіть пароль" required>
            <input name="avatar" type="file" class="upload-file" required>
            <span class="error"><?php $error ?></span>
		    <input name="register" class="submit-button" type="submit" value="Зареєструватись">
	    </div>
	    <a href="index.php" class="login"> Увійти? </a>
    </form>
</body>
</html>
