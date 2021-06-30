<?php 
    include("connection.php"); 
    session_start();
    $_SESSION["update_error"] = "";
?>

<?php
    if (isset($_POST['button_cancel'])){
        header("location: index.php");
    }

    if (isset($_POST['button_action'])){
        $error = "";
        
        $worker_id = $_POST['worker_id'];

        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $patronymic = trim($_POST['patronymic']);
        $email =  trim($_POST['email']);
        $current_password = $_POST['current_password'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];

        $image = $_FILES['avatar']['tmp_name'];
        $blob = addslashes(file_get_contents($image));

        $phone_number = trim($_POST['phone_number']);
        $position = trim($_POST['position']);
        $state = trim($_POST['state']);
        
        $isImage = false;

        if (empty($first_name)){
            $error = "Поле \"Ім'я\" не може бути пустим";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }

        if (empty($last_name)){
            $error = "Поле \"Прізвище\" не може бути пустим";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }

        if (empty($patronymic)){
            $error = "Поле \"По батькові\" не може бути пустим";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }

        if (empty($email)){
            $error = "Поле \"Електронна пошта\" не може бути пустим";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }


        if (!empty($old_password) && !empty($new_password) ){
            if (md5($old_password) == $current_password){
                $current_password = md5($new_password);
            }else {
                $error = "Паролі не співпадають";
                $_SESSION["update_error"] = $error;
                header("location: update-worker-action.php?edit=$worker_id");
            }
        }

        if ($_FILES['avatar']['name'] === ''){
            header("location: update-worker-action.php?edit=$worker_id");
        }else if ($_FILES['avatar']['size'] > 16777214) {
            $error = "Зображення занадто велике";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }else if (!preg_match('/.*\.jpg$/',$_FILES['avatar']['name'])){
            $error = "Файл некоректний!(доступні розширення: jpeg, png)";
            $_SESSION["update_error"] = $error;
            header("location: update-worker-action.php?edit=$worker_id");
        }else {
            $isImage=true;
        }

        if($error == ""){
            if ($isImage == true) {
                $query = "UPDATE `workers` SET `first_name`='$first_name', `last_name`='$last_name' ,`patronymic`='$patronymic', `email`='$email', `photo`='$blob',`password`='$current_password' WHERE `worker_id`=$worker_id";
                $statement = $connect->prepare($query);
                $statement->execute();
            }else{
                $query = "UPDATE `workers` SET `first_name`='$first_name', `last_name`='$last_name' ,`patronymic`='$patronymic', `email`='$email', `password`='$current_password' WHERE `worker_id`= $worker_id";
                $statement = $connect->prepare($query);
                $statement->execute();
            }

            $query = "UPDATE `additional_info` SET `phone_number`='$phone_number', `position`='$position' ,`state`='$state' WHERE `worker_id`=$worker_id";
            $statement = $connect->prepare($query);
            $statement->execute();
            header("location: update-worker-action.php?edit=$worker_id");
        }
    }
?>