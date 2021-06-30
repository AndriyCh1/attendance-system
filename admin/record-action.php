<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записи</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
   <?php include("header.php"); ?>
   <div class="container">

    <div id="records-table">
    <table class="table records-table">
        <thead>
            <tr>
                <th><a href="#" id="last_name" class="column-sort" data-sort="asc">ПІП <span> &#9651;</span></a></th>
                <th><a href="#" id="date" class="column-sort" data-sort="asc">Дата</a></th>
                <th style="width: 20px;">Прибуття</th>
                <th>Час прибуття</th>
                <th style="width: 20px;">Відхід</th>
                <th>Час відходу</th>     
                <th></th>     
                <th style="width: 60px;"></th>     
            </tr>
        </thead>
        <tbody>
           <?php 

                $query = "SELECT * FROM records INNER JOIN workers ON records.worker_id = workers.worker_id ORDER BY record_id desc LIMIT 80";
                $statement = $connect->prepare($query);
                $selectedUser = 0;
                if($statement->execute()){
		            $rows_count = $statement->rowCount();
                    if ($rows_count > 0){
                        $rows = $statement->fetchAll();
                        foreach ($rows as $row) { ?>
                        <tr>
                            <td> <?php echo $row['last_name'] ." ". substr($row['first_name'], 0, 2).".".substr($row['patronymic'], 0, 2). "."; ?></td>
                            <td> <?php echo $row['date']; ?> </td>
                            <td> <?php echo $row['status_arrival']; ?> </td>
                            <td> <?php echo $row['time_arrival']; ?> </td>
                            <td> <?php echo $row['status_leaving']; ?> </td>
                            <td> <?php echo $row['time_leaving']; ?> </td>
                            <td><a href="#" class="edit-record" data-record-id="<?php echo $row['record_id'];?>"><span style="color: green;">&#x0270E;</span></a></td>
                            <td><a href="#" class="delete-record" data-record-id="<?php echo $row['record_id'];?>"><span style="color: red;">&#10007;</span></a></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                <?php }; ?>
        </tbody>
    </table>
    
  

    </div>
    <div class="filter">
        <input class="search" type="text" placeholder="Прізвище">
    </div>
   </div>


<script>  
 $(document).ready(function(){  
     
      $(document).on('click', '.column-sort', function(){  
           let column_name = $(this).attr("id");  
           let order = $(this).data("sort");  
           let arrow = '';  

           if(order == 'desc'){  
            arrow = '<span> &#9651;</span>';  
           }else {  
            arrow = '<span> &#9661;</span>';  
           }  

           $.ajax({  
                url:"sort-records-table.php",  
                method:"POST",  
                data:{column_name:column_name, order:order},  
                success:function(data)  
                {  
                     $('#records-table').html(data);  
                     $('#'+column_name+'').append(arrow);  
                },
           })  
      });  


      $(document).on('click', '.delete-record', function(){ 
        let record_id = $(this).data("record-id");
        $.ajax({  
                url:"delete-record.php",  
                method:"POST",  
                data:{
                    record_id:record_id
                },
        })

        $("a[data-record-id='"+record_id+"'").toggleClass("disabled");
        $(this).closest("tr").remove();
      });


        $(document).on('click', '.edit-record', function(){  
            $(this).toggleClass("disabled");
            let record_id = $(this).data("record-id");
            let row_values = $(this).closest("tr").children();

            let edit_row ="";
            edit_row += "<tr>";
            edit_row += "<td></td>";
            edit_row += "<td><input name='date'  type=\"text\" value=\""+row_values.eq(1).html()+"\"/></td>";
            edit_row += "<td><input name='status_arrival' type=\"text\" value=\""+row_values.eq(2).html()+"\"/></td>";
            edit_row += "<td><input name='time_arrival' type=\"text\" value=\""+row_values.eq(3).html()+"\"/></td>";
            edit_row += "<td><input name='status_leaving' type=\"text\" value=\""+row_values.eq(4).html()+"\"/></td>";
            edit_row += "<td><input name='time_leaving' type=\"text\" value=\""+row_values.eq(5).html()+"\"/></td>";
            edit_row += "<td><a href=\"#\" class=\"save-edit\" data-record-id=\""+record_id+"\">OK </a></td>"
            edit_row += "<td><a href=\"#\" class=\"cancel-edit\" data-record-id=\""+record_id+"\">Скасувати</a></td>";
            edit_row += "</tr>";

            $(this).closest("tr").after(edit_row);
        });  

        $(document).on('click', '.cancel-edit', function(){  
            let record_id = $(this).data("record-id");
            $("a[data-record-id='"+record_id+"'").toggleClass("disabled");
            $(this).closest("tr").remove();
        });

        $(document).on('click', '.save-edit', function(){ 
            let record_id = $(this).data("record-id");
            let row_values = $(this).closest("tr").find( "input" );
                $.ajax({  
                url:"edit-record.php",  
                method:"POST",  
                data:{
                    record_id:record_id, 
                    date:row_values.eq(0).val(),
                    status_arrival: row_values.eq(1).val(),
                    time_arrival: row_values.eq(2).val(),
                    status_leaving: row_values.eq(3).val(),
                    time_leaving: row_values.eq(4).val()
                },

                success:function()  
                {  
                     location.href = "<?php echo $baseUrl;?>record-action.php";  
                },
           })
        });

        $(document).on('keyup', '.search', function(){ 
           let filter_input = $(this).val();  

           $.ajax({  
                url:"filter-records-table.php",  
                method:"POST",  
                data:{filter_input:filter_input},  
                success:function(data)  
                {   
                     $('#records-table').html(data);  
                },
           })  

         })


 });  
 </script>
 </body>
</html>