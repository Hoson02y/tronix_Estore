<?php  include '../ba/header.php' ?>
<?php
session_start();
include "../ba/DBconn.php"; // Including the file for database connection
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <?php include '../ba/head.php' ?>

</head>
<body>
  <br>
  <section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <br>
    <br>
    <br>
    <br>
    <div class="h1 mb-4">
    Email already in use. Please use another email.
    </div>
    <div class="try-again-link">
        <h2><a href="register-cust.php">Try Again</a></h2>
   
</div>
     

</section>  
</body>
</html>
 <?php  include '../ba/footer.php' ?>