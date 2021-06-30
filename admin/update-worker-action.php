<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
</head>
<body>

<?php include("header.php");?>
<div class="action">
    <div class="container">
        <form action="update-worker.php" method="post" enctype="multipart/form-data">
            <div class="form-action">
                <?php if($_SERVER["REQUEST_METHOD"] == "GET"){ 
                    if(isset($_GET['edit'])){ 
                    $query = "SELECT * FROM workers 
                              INNER JOIN additional_info ON workers.worker_id = additional_info.worker_id 
                              WHERE workers.worker_id =".$_GET['edit'];
                    $statement = $connect->prepare($query);
                    if($statement->execute()){
                        $rows_count = $statement->rowCount();
                        if ($rows_count > 0){
                            $rows = $statement->fetchAll();
                            $row = $rows[0]; ?>
                            <div class="action-header">
                                <p class="action-title"> <?php echo $row['last_name']." ".$row['first_name']." ".$row['patronymic']; ?></p>
                            </div>
                                <div class="action-body">
                                    <div class="action-fields">
                                        <div class="error">
                                            <?php if(isset($_SESSION['update_error'])){ 
                                                echo $_SESSION['update_error'];
                                                unset($_SESSION['update_error']); 
                                            }?>
                                        </div>
                                        <input type="hidden" name="worker_id" value="<?php echo $row['worker_id']; ?>">
                                        <input type="hidden" name="current_password" value="<?php echo $row['password']; ?>">
                                        <div>
                                            <label class="input-label">Прізвище</label>
                                            <input name="last_name" type="text" class="last-name" value="<?php echo $row['last_name']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">Ім'я</label>
                                            <input name="first_name" type="text" class="first-name" value="<?php echo $row['first_name']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">По батькові</label>
                                            <input name="patronymic" type="text" class="patronymic" value="<?php echo $row['patronymic']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">Електронна пошта</label>
                                            <input name="email" type="email" class="email" value="<?php echo $row['email']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">Старий пароль</label>
                                            <input name="old_password" class="password" type="password">
                                        </div>
                                        <div>
                                            <label class="input-label">Новий пароль </label>
                                            <input name="new_password" class="password" type="password">
                                        </div>
                                        <div>
                                            <label class="input-label">Завантажити фото</label>
                                            <input name="avatar" type="file" class="upload-file">
                                        </div>
                                        <div>
                                            <label class="input-label">Номер телефону</label>
                                            <input name="phone_number" type="text" class="phone-number" value="<?php echo $row['phone_number']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">Посада</label>
                                            <input name="position" type="text" class="position" value="<?php echo $row['position']; ?>">
                                        </div>
                                        <div>
                                            <label class="input-label">Стать</label>
                                            <input name="state" type="text" class="state" value="<?php echo $row['state']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="action-worker-image">
                                    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['photo']);?>"/>
                                </div>
                                <div class="action-footer">
                                    <input type="submit" name="button_action" class="button-action" value="Зберегти зміни"/>
                                    <input type="submit" name="button_cancel" class="button-close" value="Скасувати">
                                </div>
                                <?php } } ?>
                            </div>
                        </form>
                    </div>
                </div>
                <?php }else{ header("location:index.php");
                } 
            }else{ header("location:index.php");
        } ?>
</body>
</html>
