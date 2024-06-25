<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username'])|| $_SESSION['user_type'] !='customer') {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include '../ba/head.php' ?>

<body>

  <?php include '../ba/header-cust.php' ?>

       <br>
       <br>
       <br>



<?php include '../ba/Purchase_Summary_Report.php' ?>

<?php include '../ba/footer.php' ?>
</body>

</html>
