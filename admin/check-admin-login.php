<?php 
    include('connection.php');
    session_start();

    $admin_name = '';
    $admin_password = '';
    $error_admin_name = '';
    $error_admin_password = '';

    $error = FALSE;

    if(empty($_POST["admin_name"])){
        $error_admin_name = "Введіть логін";
        $error = TRUE;
    }else{
        $admin_name = $_POST["admin_name"];
    }

    if(empty($_POST["admin_password"])){
        $error_admin_password = "Введіть пароль";
        $error = True;
    }else{
        $admin_password = $_POST["admin_password"];
    }

    if(!$error){
        $query = "SELECT * FROM admins WHERE admin_name = '".$admin_name."'";
        $statement = $connect->prepare($query);
        if($statement->execute()){
		    $rows_count = $statement->rowCount();
            if ($rows_count > 0){
                $rows = $statement->fetchAll();
                foreach ($rows as $row) {
                    if(md5($admin_password) == $row["admin_password"]){
                        $_SESSION["adminId"] = $row["admin_id"];
                    }
                    else {
                        $error_admin_password = "Пароль неправильний!";
                        $error=TRUE;
                    }
                }
            }else {
                $error_admin_name = "Користувача не знайдено";
                $error = TRUE;
            }
        }
    }

    if($error == TRUE){
        $output = array(
            'error'					=>	true,
            'error_admin_name'	    =>	$error_admin_name,
            'error_admin_password'	=>	$error_admin_password
        );
    }else{
        $output = array(
            'success'		=>	true
        );	
    }

    echo json_encode($output);
?>
