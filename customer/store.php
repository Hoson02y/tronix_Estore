<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username'])|| $_SESSION['user_type'] !='customer') {
    header("Location: logout.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<?php include '../ba/head.php' ?>

<body>

  <?php include '../ba/header-cust.php' ?>


       <br>
       <br>
       <br>
    

       <section id="home" class="portfolio">

<div class="container" data-aos="fade-up">

  <header class="section-header">
<br>
    <p color="gray"><span>Store 1</span>: Check Out <span>Our</span> Products</p>
  </header>


  </div>

</div>
<div class="container" data-aos="fade-up">


  <div class="row gy-6">

    <div class="col-lg-6">

      <div class="row gy-4">
        <div class="col-md-12 portfolio-item filter-web">
   
      <div class="portfolio-wrap">
        <img src="assets/img/store1.jpg" class="img-fluid" alt="">
        <div class="portfolio-info">
          <h4></h4>
      
        
         <div class="portfolio-link">
           
          </div>
            <div class="portfolio-links">
          </div>

       
      </div>
    </div>

        </div>
       
      </div>

    </div>

    <div class="col-lg-6">
      <form action="forms/contact.php" method="post" class="php-email-form">
        <div class="row gy-4">

        <div class="col-md-6 portfolio-item filter-web">
   
      <div class="portfolio-wrap">
        <img src="assets/img/img1.jpg" class="img-fluid" alt="">
        <div class="portfolio-info">
          <h4>Discover a world of possibilities with Apple</h4>
      
        
         <div class="portfolio-link">
           
            <a href="" title="More Details" >Apple</a>
          </div>
            <div class="portfolio-links">
            <a href="assets/img/img1.jpg" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
          </div>
      </div>
    </div>

        </div>
       
       <div class="col-md-6 portfolio-item filter-web">
   
      <div class="portfolio-wrap">
      <img src="assets/img/img3.jpg" class="img-fluid" alt="">
        <div class="portfolio-info">
          <h4>Discover a world of possibilities with sony</h4>
      
        
         <div class="portfolio-link">
           
            <a href="portfolio-details.html" title="More Details" >sony</a>
          </div>
            <div class="portfolio-links">
            <a href="assets/img/img3.jpg" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
          </div>

       
      </div>
    </div>

        </div>
       
<div class="col-md-6 portfolio-item filter-web">
   
      <div class="portfolio-wrap">
      <img src="assets/img/samsung.jpg" class="img-fluid" alt="">
        <div class="portfolio-info">
          <h4>Discover a world of possibilities with samsung</h4>
      
        
         <div class="portfolio-link">
           
            <a href="portfolio-details.html" title="More Details" >samsung</a>
          </div>
            <div class="portfolio-links">
            <a href="assets/img/samsung.jpg" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
          </div>

       
      </div>
    </div>

        </div>
          <div class="col-md-6 portfolio-item filter-web">
   
      <div class="portfolio-wrap">
      <img src="assets/img/Huawei.jpg" class="img-fluid" alt="">
        <div class="portfolio-info">
          <h4>Discover a world of possibilities with Huawei</h4>
      
        
         <div class="portfolio-link">
           
            <a href="portfolio-details.html" title="More Details" >Huawei</a>
          </div>
            <div class="portfolio-links">
            <a href="assets/img/Huawei.jpg" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
          </div>

       
      </div>
    </div>

        </div>

        </div>
      </form>

    </div>

  </div>

</div>
</section>

<?php include '../ba/stores1.php' ?>

<?php include '../ba/footer.php' ?>
</body>
</html>