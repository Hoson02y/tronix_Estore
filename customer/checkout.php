<?php
session_start();

// Include database connection
require_once '../ba/DBconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Get recipient information from the form
        $recipientName = $_POST['recipient_name'];
        $recipientPhone = $_POST['recipient_phone'];
        $recipientAddress = $_POST['recipient_address'];
        
        // Calculate total items and total price from the cart
        $totalItems = count($_SESSION['cart']);
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        
        try {
            // Begin a transaction
            $conn->beginTransaction();
            
            // Insert order information into the orders table
            $orderDate = date('Y-m-d H:i:s'); // Current date and time
            $customerId = $_SESSION['user_id']; // Assuming user_id is set in the session
            $sqlOrder = "INSERT INTO `orders`(`customer_id`, `RecipientName`, `RecipientPhone`, `RecipientAddress`, `NO_Items_required`, `order_date`) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmtOrder = $conn->prepare($sqlOrder);
            $stmtOrder->execute([$customerId, $recipientName, $recipientPhone, $recipientAddress, $totalItems, $orderDate]);
            $orderId = $conn->lastInsertId(); // Get the last inserted order ID
            
            // Insert order items into the order_items table
            $sqlOrderItems = "INSERT INTO `order_items`(`order_id`, `product_id`, `quantity`, `TotalPrice`) VALUES (?, ?, ?, ?)";
            $stmtOrderItems = $conn->prepare($sqlOrderItems);
            foreach ($_SESSION['cart'] as $productId => $item) {
                $stmtOrderItems->execute([$orderId, $productId, $item['quantity'], $item['price'] * $item['quantity']]);
            }
            
            // Update product quantities in the products table
            $sqlUpdateProducts = "UPDATE products SET quantity_available = quantity_available - ?, quantity_Sold = quantity_Sold + ? WHERE id = ?";
            $stmtUpdateProducts = $conn->prepare($sqlUpdateProducts);
            foreach ($_SESSION['cart'] as $productId => $item) {
                $stmtUpdateProducts->execute([$item['quantity'], $item['quantity'], $productId]);
            }
            
            // Commit the transaction
            $conn->commit();
            
            // Clear the cart after successful checkout
            unset($_SESSION['cart']);
            
            // Redirect to a success page or display a success message
            header("Location: home-cust.php");
            exit();
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Redirect to the shopping cart page if the cart is empty or user is not logged in
        header("Location: home-cust.php");
        exit();
    }
} else {
    // Redirect to the shopping cart page if accessed directly without a POST request
    header("Location: home-cust.php");
    exit();
}
?>
