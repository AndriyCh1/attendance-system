<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записи</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include("header.php"); ?>
   <div class="container">
    <div id="records-table">
    <table class="table records-table">
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
                $query = "SELECT * FROM records 
                          INNER JOIN workers ON records.worker_id = workers.worker_id 
                          WHERE records.worker_id=".$_SESSION['worker_id']." ORDER BY date DESC LIMIT 80";
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
   </div>

  </body>
</html>