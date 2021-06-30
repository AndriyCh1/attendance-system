<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Профіль  </title>
</head>
<body>

<?php include("header.php") ?> 

<div class="container">
    <div class="profile">
    <?php 
        $query = "SELECT * FROM workers INNER JOIN additional_info ON workers.worker_id = additional_info.worker_id WHERE workers.worker_id =".$_SESSION['worker_id'];
        $statement = $connect->prepare($query);

        if($statement->execute()){
            $rows_count = $statement->rowCount();
            if ($rows_count > 0){
                $rows = $statement->fetchAll();
                foreach ($rows as $row) { ?>
                   <div class="profile-image">
                       <img height='400' src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['photo']); ?>"/> 
                   </div>
                   <div class="profile-info">
                        <div class="profile-name"> 
                            <?php 
                                echo "<p> ПІП: </p> <p>";
                                echo $row['last_name']." ".$row['first_name']." ".$row['patronymic']; 
                                echo "</p>";
                            ?>
                        </div>
                        <div class="profile-email">
                            <p>Електронна адреса: </p>
                            <p><?php echo $row['email'] ?></p>
                        </div>

                        <div class="profile-number">
                            <?php 
                                if(!empty($row['phone_number'])){
                                    echo "<p>"."Номер телефону:"."</p>";
                                    echo "<p>". $row['phone_number'] ."</p>";
                                }
                            ?>
                        </div>
                        <div class="profile-position">
                            <?php 
                                if(!empty($row['position'])){
                                    echo "<p>"."Посада: "."</p>";
                                    echo "<p>". $row['position'] ."</p>";
                                }
                            ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        </div>
                   </div>
    </div>

    <table class="table profile-table"> 
        <!-- records-table -->
        <thead>
            <tr>
                <th>Н/п</a></th>
                <th>Дата</a></th>
                <th>Прибуття</th>
                <th>Час прибуття</th>
                <th>Відхід</th>
                <th>Час відходу</th>     
            </tr>
        </thead>
        <tbody>

           <?php 
                $query = "SELECT * FROM records INNER JOIN workers ON records.worker_id = workers.worker_id WHERE records.worker_id=".$_SESSION['worker_id']." ORDER BY record_id desc LIMIT 5";
                $statement = $connect->prepare($query);
                $selectedUser = 0;
                if($statement->execute()){
		            $rows_count = $statement->rowCount();
                    if ($rows_count > 0){
                        $rows = $statement->fetchAll();
                        $index = 0;
                        foreach ($rows as $row) { $index += 1;?>
                        <tr>
                            <td> <?php echo $index ?> </td>
                            <td> <?php echo $row['date']; ?> </td>
                            <td> <?php 
                                    if($row['status_arrival']=='1'){echo "<span style='color:green;'>&#10004;</span>"; }
                                    else{echo "<span style='color:red;'>&#10006;</span>"; }
                                 ?> 
                            </td>
                            <td> <?php echo $row['time_arrival']; ?> </td>
                            <td>  
                                <?php 
                                    if($row['status_leaving']=='1'){echo "<span style='color:green;'>&#10004;</span>"; }
                                    else{echo "<span style='color:red;'>&#10006;</span>"; }
                                 ?>  
                            </td>
                            <td> <?php echo $row['time_leaving']; ?> </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                <?php }; ?>
        </tbody>
    </table>
    </div>

</body>
</html>
