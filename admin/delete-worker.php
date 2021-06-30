<?php 
    include('connection.php');
     if($_SERVER["REQUEST_METHOD"] == "GET"){
         if(isset($_GET['delete'])){ 
            $query = "DELETE FROM `workers` WHERE `worker_id`=".$_GET['delete'];
            $statement = $connect->prepare($query);
            $statement->execute();
            header("location:index.php");
        }   
    }
?>