<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../js/jquery.min.js"></script>
    <title>Головна</title>
</head>
<body>

<?php include("header.php") ?> 
<div class="container">
    <table class="table workers-table">
        <thead>
        <tr>
              <th>Фото</th>
              <th>Прізвище</th>
              <th>Ім'я</th>
              <th>По батькові</th>
              <th>Електронна пошта</th>
              <th></th>
              <th></th>
        </tr>
        </thead>
        <tbody>
           <?php 
                $query = "SELECT * FROM workers";
                $statement = $connect->prepare($query);
                $selectedUser = 0;
                if($statement->execute()){
		            $rows_count = $statement->rowCount();
                    if ($rows_count > 0){
                        $rows = $statement->fetchAll();
                        foreach ($rows as $row) { ?>
                        <tr>
                            <td><img width='100' src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['photo']);?>"/></td>
                            <td> <?php echo $row['last_name']; ?> </td>
                            <td> <?php echo $row['first_name']; ?> </td>
                            <td> <?php echo $row['patronymic']; ?> </td>
                            <td> <?php echo $row['email']; ?> </td>
                            <!-- <td><a href="#">View</a></td> -->
                            <!-- <input type="hidden" id="worker-modal-id"> -->
                            <td><a href="update-worker-action.php?edit=<?php echo $row['worker_id'];?>" class="edit-worker"><span style="color: green; ">&#x0270E;</span></a></td>
                            <td><a href="delete-worker.php?delete=<?php echo $row['worker_id'];?>" class="delete-worker"><span style="color: red;">&#10007;</span></a></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                <?php }; ?>
        </tbody>
    </table>
</div>

   
</body>
</html>


<script> 
// $(document).ready(function() {
	// $('.edit-index').click(function() {
	// 	$('.modal').fadeIn();
    //     location.href = "<php echo $baseUrl;?>edit.php?edit"+$(this).data('worker-id');
    //     // $('#worker-modal-id').val($(this).data('worker-id'));
        
	// 	return false;
	// });	
	
	// $('.close-modal').click(function() {
	// 	$(this).parents('.modal').fadeOut();
	// 	return false;
	// });		
 
	// $(document).keydown(function(e) {
	// 	if (e.keyCode === 27) {
	// 		e.stopPropagation();
	// 		$('.modal').fadeOut();
	// 	}
	// });
	
	// $('.modal').click(function(e) {
	// 	if ($(e.target).closest('.modal-content').length == 0) {
	// 		$(this).fadeOut();					
	// 	}
	// });


//     $('.login-form').on('submit',function(event) {
// 			event.preventDefault();

// 			$.ajax({
// 				url:"check-admin-login.php",
// 				method: "POST",
// 				data:$(this).serialize(),
// 				dataType:"json",
// 				success:function(data){
// 					if(data.success){
// 						location.href = "<php echo $baseUrl;?>index.php";
// 					}
// 					if(data.error){
// 						if(data.error_admin_name != ''){
// 							$('.error-admin-name').text(data.error_admin_name);
// 						}else{
// 							$('.error-admin-name').text('');
// 						}

// 						if(data.error_admin_password != ''){
// 							$('.error-admin-password').text(data.error_admin_password);
// 						}
// 						else{
// 							$('.error-admin-password').text('');
// 						}
// 					}
// 				}
// 			})
// 		})
// });
		
 </script>