<?php
session_start(); // Access session variables


// 'ba/header-cust.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <?php include 'ba/head.php'; ?>
</head>
<body>
    <section id="about" class="about">

        <?php include $header_to_include; ?>

    <?php include 'header-cust.php'; ?>

<div class="container" data-aos="fade-up">
<div class="row gx-0">
<div class="col-lg-2">
  <!-- Content for column 2 -->
</div>
  <div class="col-lg-8 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
    <div class="content">
      <h3>Who We Are</h3>
      <h2>Troxin: Your One-Stop Shop for the Latest Tech Gadgets</h2>
      <p>
        At Troxin, we believe in the power of technology to transform lives. Our mission is to provide our customers with the latest tech-related products, ensuring they stay ahead of the curve. We specialize in a wide range of gadgets, from the latest smartphones and laptops to innovative wearables and home automation devices. Our commitment is to offer high-quality products at competitive prices, backed by exceptional customer service.
      </p>
      <div class="text-center text-lg-start">
        <a href="#" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
          <span>Read More</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</div>
</div>


</section><!-- End About Section -->
<?php include 'ba/footer.php'; ?>
</body>

</html>
