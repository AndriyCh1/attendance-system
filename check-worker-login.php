<?php 
    include('connection.php');
    session_start();

    $worker_email = '';
    $worker_password = '';
    $error_worker_email = '';
    $error_worker_password = '';

    $error = false;

    if(empty($_POST["worker_email"])){
        $error_worker_email = "Введіть пошту";
        $error = true;
    }else{
        $worker_email = $_POST["worker_email"];
    }

    if(empty($_POST["worker_password"])){
        $error_worker_password = "Введіть пароль";
        $error = true;
    }else{
        $worker_password = $_POST["worker_password"];
    }
    
    $worker_password = md5($worker_password);

    if(!$error){
        $query = "SELECT * FROM `workers` WHERE `email`='".$worker_email."'";
        $statement = $connect->prepare($query);

        if($statement->execute()){
		    $rows_count = $statement->rowCount();

            if ($rows_count > 0){
                $rows = $statement->fetchAll();
                foreach ($rows as $row) {
                    if($worker_password == $row["password"]){
                        $_SESSION["worker_id"] = $row["worker_id"];
                    }
                    else {
                        $error_worker_password = "Пароль неправильний!";
                        $error=true;
                    }
                }
            }else {
                $error_worker_email = "Користувача не знайдено";
                $error = true;
            }
        }
    }

    if($error == true){
        $output = array(
            'error'					=>	true,
            'error_worker_email'	=>  $error_worker_email,
            'error_worker_password'	=>	$error_worker_password,
        );
    }else{
        $output = array(
            'success'		=>	true,
        );	
    }

    echo json_encode($output);
?>
