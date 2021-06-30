<?php 
    include('connection.php');
    $record_id = $_POST['record_id'];
    $query = "DELETE FROM `records` WHERE `record_id`=".$record_id;
    $statement = $connect->prepare($query);
    $statement->execute();
    
    echo json_encode([]);
    
?>