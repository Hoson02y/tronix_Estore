<?php
session_start();

// Check if the user is logged in and has customer privileges
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'customer') {
    // Clear session data
    $_SESSION = array();

    // Delete session cookie
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

    // Destroy session
    session_destroy();

    // Redirect to login page
    header("Location: ../login_form.php");
    exit();
}

// Include database connection
require_once '../ba/DBconn.php';

// Check if the search value is empty or not
$search = "";
$products = [];

try {
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = $_GET['search'];
        $sql = "SELECT `id`, `name`, `description`, `price`, `quantity`, `image_path`, `brand`, `Item classification`, `company_id`, `quantity_available`
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

// Handle cart operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        
        // Get the product details from the cart
        $product = $_SESSION['cart'][$id];
        $availableQuantity = $product['quantity_available'];
        
        // Check if the updated quantity exceeds the available quantity
        if ($quantity <= $availableQuantity) {
            // Update the quantity in the cart
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            // Show a message or handle the situation as needed
            echo "The requested quantity exceeds the available quantity.";
        }
    }
    if (isset($_POST['remove_item'])) {
        $id = $_POST['product_id'];
        unset($_SESSION['cart'][$id]);
    }
}

// Initialize cart if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Calculate total price in cart
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Include header
include '../ba/header-cust.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Include CSS styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #ff6600;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        table th {
            background-color: #ff6600;
            color: #fff;
            text-transform: uppercase;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        h3 {
            text-align: center;
            margin: 20px;
            color: #ff6600;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #ff6600;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #e65c00;
        }

        .empty-cart {
            text-align: center;
            margin-top: 50px;
            font-size: 18px;
            color: #6c757d;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-remove {
            background-color: #dc3545;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }
    </style>
    <!-- Include head.php for common HTML head content -->
    <?php include '../ba/head.php'; ?>
</head>

<body>
    <!-- Include header content -->
    <?php include '../ba/header-cust.php'; ?>

    <!-- Shopping Cart Section -->
    <section class="shopping-cart">
        <div class="container">
            <h1>Shopping Cart</h1>
            <?php if (!empty($_SESSION['cart'])) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $id => $item) : ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= htmlspecialchars($item['price']) ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="1" max="<?= htmlspecialchars($item['quantity_available']) ?>">
                                        <input type="submit" name="update_quantity" value="Update">
                                    </form>
                                </td>
                                <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <input type="submit" name="remove_item" value="Remove">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h3>Total: <?= htmlspecialchars($total) ?></h3>
                <input type="button" name="checkout" value="Confirm Order" data-toggle="modal" data-target="#checkoutModal">

            <?php else : ?>
                <p>Your shopping cart is empty.</p>
            <?php endif; ?>
        </div>
    </section>
 <!-- Checkout Modal -->
 <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Recipient Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="checkout.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipientName">Full Name</label>
                            <input type="text" class="form-control" id="recipientName" name="recipient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="recipientPhone">Phone Number</label>
                            <input type="tel" class="form-control" id="recipientPhone" name="recipient_phone" required>
                        </div>
                        <div class="form-group">
                            <label for="recipientAddress">Address</label>
                            <textarea class="form-control" id="recipientAddress" name="recipient_address" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Include footer content -->
    <?php include '../ba/footer.php'; ?>

    <!-- JavaScript code -->
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
</body>

</html>
