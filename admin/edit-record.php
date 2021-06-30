<?php 
   include('connection.php');

    $record_id = $_POST["record_id"];  
    $date = $_POST['date'];
    $status_arrival = $_POST['status_arrival'];
    $time_arrival = $_POST['time_arrival'];
    $status_leaving = $_POST['status_leaving'];
    $time_leaving = $_POST['time_leaving'];

    $query = "UPDATE `records` SET `status_arrival` = '$status_arrival', `date` = '$date', `time_arrival` = '$time_arrival', `status_leaving` = '$status_leaving',`time_leaving` = '$time_leaving' WHERE `record_id` = $record_id";
    $statement = $connect->prepare($query);
    $statement->execute();
   
    echo json_encode([]);

?>