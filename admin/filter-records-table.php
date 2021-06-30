<?php  
 include("connection.php") ;
 $filter_input = $_POST["filter_input"];  
 

 $query = "SELECT * FROM records INNER JOIN workers ON records.worker_id = workers.worker_id WHERE `last_name` LIKE '%".$filter_input. "%'";

 $output = '  
 <table class="table records-table"> 
 <thead>
     <tr> 
             <th><a href="#" id="last_name" class="column-sort" data-sort="desc">ПІП</a></th>
             <th><a href="#" id="date" class="column-sort" data-sort="desc">Дата</a></th>
             <th>Прибуття</th>
             <th>Час прибуття</th>
             <th>Відхід</th>
             <th>Час відходу</th>  
             <th></th>     
             <th></th>     

     </tr>  
 <thead>

';  

$output .= '<tbody>';  

$statement = $connect->prepare($query);

 if ($statement->execute()){
    if($statement->rowCount() > 0){
        $rows = $statement->fetchAll();
        foreach ($rows as $row) {
            $output .= '  
            <tr>
                <td>' .$row['last_name'] ." ". substr($row['first_name'], 0, 2).".".substr($row['patronymic'], 0, 2). ".".'</td>
                <td>' .$row['date'].'</td>
                <td>' .$row['status_arrival'].'</td>
                <td>' .$row['time_arrival'].'</td>
                <td>' .$row['status_leaving'].'</td>
                <td>' .$row['time_leaving'].'</td>
                <td><a href="#" class="edit-record" data-record-id="'.$row['record_id'].'"><span style="color: green;">&#x0270E;</span></a></td>
                <td><a href="#" class="delete-record" data-record-id="'.$row['record_id'].'"><span style="color: red;">&#10007;</span></a></td>
            </tr>  
            ';  
        }
    }
}  
$output .= '<tbody>';  
$output .= '</table>';  

echo $output;  
?>  