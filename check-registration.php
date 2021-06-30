<?php
    session_start();
    require_once('connection.php');
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $patronymic = $_POST['patronymic'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm= $_POST['password_confirm'];

    if ( isset($_POST['register']) ){
        if ($password === $password_confirm) {
           

            $image = $_FILES['avatar']['tmp_name'];
            $blob = addslashes(file_get_contents($image));
            $password = md5($password);

            $query = "INSERT INTO `workers` (`first_name`, `last_name`, `patronymic`, `email`, `photo`, `password`) VALUES ('$first_name', '$last_name', '$patronymic', '$email', '$blob','$password')";
            $statement = $connect->prepare($query);
            $statement->execute();

            $query = "SELECT worker_id FROM `workers` WHERE `email`='$email' AND `password`='$password'";
            
            $worker_id = -1;
            $statement = $connect->prepare($query);
            if ($statement->execute()){
                if($statement->rowCount()>0){
                    $worker_id = $statement->fetchAll()[0]['worker_id'];
                }
            }

            if ($worker_id!=-1){
                $query = "INSERT INTO `additional_info` (`worker_id`) VALUES ('$worker_id')";
                $statement = $connect->prepare($query);
                $statement->execute();
            }

            $_SESSION['message'] = "Реєстрація пройшла вдало!".$worker_id;
            $_SESSION['worker_id'] = $worker_id;
            header('Location: index.php');
        } else {
            $_SESSION['message'] = 'Паролі не співпадають, мб ще щось';
            header('Location: registration.php');
        }
        
    }
?>

