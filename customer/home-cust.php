<?php
session_start();

// التحقق من تسجيل الدخول وصلاحيات المستخدم
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'customer') {
    
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    header("Location: ../login_form.php");
    exit();
}

require_once '../ba/DBconn.php';

$search = "";
$products = [];

try {
    // التحقق مما إذا كانت قيمة البحث فارغة أم لا
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = $_GET['search'];
        $sql = "SELECT `id`, `name`, `description`, `price`, `old_price`, `quantity`, `image_path`, `brand`, `Item classification`, `company_id`, `quantity_available`
                FROM `products`
                WHERE `quantity_available` > 0
                AND (`name` LIKE :search OR `brand` LIKE :search OR `Item classification` LIKE :search)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    } else {
        $sql = "SELECT `id`, `name`, `description`, `price`, `quantity`, `image_path`, `brand`, `Item classification`, `company_id`, `quantity_available`
                FROM `products`
                WHERE `quantity_available` > 0";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tronix</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    margin: 0 auto;
    max-width: 1200px;
    padding: 20px;
}

.filter-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.filter-section form {
    display: flex;
    width: 100%;
}

.filter-section input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-right: 10px;
}

.filter-section input[type="submit"] {
    padding: 10px 15px;
    background-color: #ff6600;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
.btn.btn-primary, .addToCartBtn {
    background-color: #ff6600;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    font-size: 16px;
    margin: 15px 0;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn.btn-primary:hover, .addToCartBtn:hover {
    background-color: #ff4500;
}


.filter-section {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    padding: 5px 10px;
    border-radius: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    max-width: 600px;
    margin: 0 auto 20px auto;
}

.filter-section form {
    display: flex;
    align-items: center;  
    width: 100%;
}

.search-label {
    font-size: 12px;
    color: #333;
    margin-right: 10px; 
    position: relative;
    top: -2px;  
}

.filter-section input[type="text"] {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 20px;
    margin-right: 5px;
    font-size: 14px;
}

.filter-section input[type="submit"] {
    padding: 8px 15px;
    background-color: #ff6600;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
}

.filter-section input[type="submit"]:hover {
    background-color: #ff4500;
}


.product-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.product-box {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease;
    width: calc(33.333% - 20px);
    margin-bottom: 20px;
}

.product-box:hover {
    transform: translateY(-5px);
}

.product-box img {
    width: 100%;
    height: auto;
    display: block;
}

.product-box h3 {
    font-size: 1.2em;
    margin: 15px 0;
    color: #333;
}

.product-box p {
    font-size: 1em;
    color: #777;
    margin: 10px 0;
}

.btn {
    background-color: #ff6600;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    font-size: 16px;
    margin: 15px 0;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn:hover {
    background-color: #ff4500;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 10px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

.modal-body {
    text-align: center;
}

.modal-body h3 {
    margin: 10px 0;
}

.modal-body p {
    margin: 5px 0;
}

@media (max-width: 768px) {
    .product-container {
        flex-direction: column;
        align-items: center;
    }

    .product-box {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease;
    width: calc(25px - 15px);
        margin-bottom: 20px;
}

}

    </style>
</head>

<body>
    <!-- Header Section -->

    <?php //include '../ba/header-cust.php'; ?>

    <?php include 'header-cust.php'; ?>

    <br><br><br>


    <section id="home" class="portfolio">
        <div class="container" data-aos="fade-up">
            
                <br>
               
            </header>
        </div>
        <section id="home" class="portfolio">
      <div class="container" data-aos="fade-up">

        <header class="section-header">
     <br>
     <p color="gray"><span>Get</span> the Latest Electronics <span>Straight</span> to<span>You</span></p>
        </header>

        


        </div>

      </div>
 <div class="container" data-aos="fade-up">


        <div class="row gy-6">

          <div class="col-lg-6">

            <div class="row gy-4">
              <div class="col-md-12 portfolio-item filter-web">
         
            <div class="portfolio-wrap">
              <img src="assets/img/front.jpg" class="img-fluid" alt="">
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
            <form action="contact.php" method="post" class="php-email-form">
              <div class="row gy-4">

              <div class="col-md-6 portfolio-item filter-web">
         
             <div class="portfolio-wrap">
              <img src="assets/img/Apple.webp" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Discover a world of possibilities with Apple</h4>
            
              
               <div class="portfolio-link">
                 
                  <a href="" title="More Details" >Apple</a>
                </div>
                  <div class="portfolio-links">
                  <a href="assets/img/Apple.webp" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
                </div>
            </div>
          </div>

              </div>
             
             <div class="col-md-6 portfolio-item filter-web">
         
            <div class="portfolio-wrap">
              <img src="assets/img/sony.png" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Discover a world of possibilities with Sony.</h4>
            
              
               <div class="portfolio-link">
                 
                  <a href="portfolio-details.html" title="More Details" >Sony</a>
                </div>
                  <div class="portfolio-links">
                  <a href="assets/img/sony.png" data-gallery="portfolioGallery" class="portfokio-lightbox"><i class="bi bi-plus"></i></a>
                </div>

             
            </div>
          </div>

              </div>
             
  <div class="col-md-6 portfolio-item filter-web">
         
            <div class="portfolio-wrap">
              <img src="assets/img/samsung.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Discover a world of possibilities with Samsung.</h4>
            
              
               <div class="portfolio-link">
                 
                  <a href="portfolio-details.html" title="More Details" >Samsuang</a>
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


        <!-- Main Content Section -->
        <main class="container">
            <div class="filter-section">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Search by product name, brand, or classification" value="<?php echo htmlspecialchars($search); ?>">
                    <input type="submit" value="Apply Filter">
                </form>
            </div>

            <div class="product-container">
                <?php if ($products) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product-box" data-aos="fade-up" data-aos-delay="300">
                            <a href="product.php?id=<?= $product['id'] ?>">
                                <img src="../<?= htmlspecialchars($product['image_path']) ?>" alt="Product Image">
                            </a>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p><?= htmlspecialchars(explode("\n", wordwrap($product['description'], 50, "\n"))[0]) ?></p>
                                <a href="product.php?id=<?= $product['id'] ?>" class="more">More</a>
                                <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                                <p>Quantity Available: <?= htmlspecialchars($product['quantity_available']) ?></p>

                                <!-- Add to Cart Button -->
                                <button id="addToCartBtn<?= $product['id'] ?>" class="btn btn-primary addToCartBtn">Add to Cart</button>

                                <!-- Modal for Confirming Order -->
                                <div id="modal<?= $product['id'] ?>" class="modal">
                                    <div class="modal-content">
                                        <span class="close">&times;</span>
                                        <h3>Confirm Your Order</h3>
                                        <form action="addToCart.php" method="post" class="modal-form">

                                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                                            <p><?= htmlspecialchars(explode("\n", wordwrap($product['description'], 50, "\n"))[0]) ?></p>

                                            <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                                            <p>Quantity Available: <?= htmlspecialchars($product['quantity_available']) ?></p>

                                            <label for="quantity">Quantity:</label>
                                            <input type="number" id="quantity<?= $product['id'] ?>" name="quantity" value="1" min="1" max="<?= htmlspecialchars($product['quantity_available']) ?>" onchange="calculateTotalPrice(<?= $product['id'] ?>)">
                                            <p>Total Price: $<span id="totalPrice<?= $product['id'] ?>" data-price="<?= htmlspecialchars($product['price']) ?>"><?= htmlspecialchars($product['price']) ?></span></p>
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">

                                            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No products available.</p>
                <?php endif; ?>
            </div>
        </main>



        <!-- JavaScript for Modal -->
        <script>
            
            var addToCartBtns = document.querySelectorAll('.addToCartBtn');

         
            addToCartBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var productId = this.id.replace('addToCartBtn', '');
                    var modal = document.getElementById('modal' + productId);
                    modal.style.display = "block";

                  
                    var closeBtn = modal.querySelector('.close');
                    closeBtn.onclick = function() {
                        modal.style.display = "none";
                    }

                   
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                });
            });
            
            function calculateTotalPrice(productId) {
                var quantityInput = document.getElementById('quantity' + productId);
                var totalPriceSpan = document.getElementById('totalPrice' + productId);
                var pricePerUnit = parseFloat(totalPriceSpan.dataset.price); 
                var quantity = parseInt(quantityInput.value);
                var totalPrice = pricePerUnit * quantity;
                totalPriceSpan.innerText = totalPrice.toFixed(2);
            }
        </script>


        <?php include '../ba/stores.php'; ?>
        <?php include '../ba/about.php'; ?>
        <?php include '../ba/footer.php'; ?>
</body>

</html>
