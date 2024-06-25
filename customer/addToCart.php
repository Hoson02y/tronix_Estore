<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'customer') {
    header("Location: ../login_form.php");
    exit();
}

// التحقق من أن الطلب هو طلب POST وأن الزر 'add_to_cart' قد تم الضغط عليه
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity']; // تحويل الكمية إلى عدد صحيح

    // التحقق من وجود سلة التسوق في الجلسة
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // إذا كانت المنتج موجود بالفعل في السلة، قم بتحديث الكمية
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        require_once '../ba/DBconn.php';
        
        // استرجاع بيانات المنتج من قاعدة البيانات
        $stmt = $conn->prepare("SELECT `name`, `price`, `image_path`, `quantity_available` FROM `products` WHERE `id` = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if 'quantity_available' key exists
        if (!isset($product['quantity_available'])) {
            $product['quantity_available'] = 0; // or handle the error as needed
        }

        // Adding product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image_path' => $product['image_path'],
            'quantity_available' => $product['quantity_available']
        ];
    }

    // إعادة التوجيه إلى صفحة السلة
    header("Location: home-cust.php");
    // إضافة رسالة تحديثية بعد عملية الإضافة لتأكيد نجاح معالجة البيانات
    exit("تمت إضافة المنتج إلى سلة المشتريات بنجاح.");
}
?>
