<?php
session_start();
// التحقق من تسجيل الدخول وصلاحيات المستخدم
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'customer') {
    // إعادة تعيين الجلسة وتدميرها
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

$filter_state = isset($_GET['filter_state']) ? $_GET['filter_state'] : '';
$filter_product_name = isset($_GET['filter_product_name']) ? $_GET['filter_product_name'] : '';

try {
    // بناء استعلام SQL مع الشروط
    $sql = "SELECT 
                oi.id AS order_item_id,
                o.id AS order_id, 
                o.customer_id, 
                o.NO_Items_required, 
                o.order_date, 
                oi.product_id, 
                oi.quantity, 
                oi.TotalPrice, 
                oi.state, 
                p.name AS product_name, 
                p.image_path, 
                p.price 
            FROM order_items oi 
            JOIN orders o ON o.id = oi.order_id
            JOIN products p ON p.id = oi.product_id
            WHERE o.customer_id = :customer_id";

    if (!empty($filter_state)) {
        $sql .= " AND oi.state = :filter_state";
    }
    if (!empty($filter_product_name)) {
        $sql .= " AND p.name LIKE :filter_product_name";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);

    if (!empty($filter_state)) {
        $stmt->bindParam(':filter_state', $filter_state, PDO::PARAM_STR);
    }
    if (!empty($filter_product_name)) {
        $filter_product_name = '%' . $filter_product_name . '%';
        $stmt->bindParam(':filter_product_name', $filter_product_name, PDO::PARAM_STR);
    }

    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$orders) {
        $orders = []; 
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}

include '../ba/head.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .theme-bg-color {
            background-color: #FF7F11;
        }

        .theme-text-color {
            color: #ffffff;
        }

        .btn-theme {
            background-color: #ffffff;
            color: #FF7F11;
        }

        .bg-gray {
            background-color: #A9A9A9;
        }

        .bg-librown {
            background-color: #D2B48C;
        }

        .list-group-item {
            background-color: #ffffff;
            border: 1px solid #ccc;
            margin-bottom: 5px;
            border-radius: 5px;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .btn-theme {
            margin-left: 10px;
        }

        .order-details {
            display: none;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .order-total {
            font-weight: bold;
        }

        .product-image {
            max-width: 250px;
            margin-right: 10px;
        }

        .order-details-container {
            display: flex;
            align-items: center;
        }

        .order-info {
            flex: 1;
        }

        /* Colors for different states */
        .state-pending {
            background-color: #FFD700; /* Yellow for pending */
        }

        .state-shipped {
            background-color: #ADD8E6; /* Light blue for shipped */
        }

        .state-delivered {
            background-color: #90EE90; /* Light green for delivered */
        }

        .state-cancelled {
            background-color: #FF6347; /* Light red for cancelled */
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include '../ba/header-cust.php'; ?>

    <section class="p-3 p-md-4 p-xl-5">
        <div class="container mt-5">
            <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Customer"; ?>!</h1>
            <form method="GET" action="">
                <div class="form-row align-items-center">
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="filter_state">Order State</label>
                        <select class="custom-select mr-sm-2" id="filter_state" name="filter_state">
                            <option value="">Choose...</option>
                            <option value="Pending" <?php if ($filter_state == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Shipped" <?php if ($filter_state == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Delivered" <?php if ($filter_state == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if ($filter_state == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="filter_product_name">Product Name</label>
                        <input type="text" class="form-control" id="filter_product_name" name="filter_product_name" value="<?php echo htmlspecialchars($filter_product_name); ?>">
                    </div>
                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-gray mb-3">
                        <div class="card-header theme-text-color">Your Orders</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <?php
                                if (empty($orders)) {
                                    echo '<li class="list-group-item">No orders found.</li>';
                                } else {
                                    foreach ($orders as $order) {
                                        $stateClass = '';
                                        switch ($order['state']) {
                                            case 'Pending':
                                                $stateClass = 'state-pending';
                                                break;
                                            case 'Shipped':
                                                $stateClass = 'state-shipped';
                                                break;
                                            case 'Delivered':
                                                $stateClass = 'state-delivered';
                                                break;
                                            case 'Cancelled':
                                                $stateClass = 'state-cancelled';
                                                break;
                                        }
                                        echo '<li class="list-group-item ' . $stateClass . '">
                                                <div class="order-details-container">
                                                    <img src="' . htmlspecialchars($order['image_path']) . '" alt="' . htmlspecialchars($order['product_name']) . '" class="product-image">
                                                    <div class="order-info">
                                                        <strong>' . htmlspecialchars($order['product_name']) . '</strong><br>
                                                        Order Date: ' . htmlspecialchars($order['order_date']) . '<br>
                                                        Unit Price: $' . htmlspecialchars(number_format($order['price'], 2)) . '<br>
                                                        Quantity: ' . htmlspecialchars($order['quantity']) . '<br>
                                                        Total Price: $' . htmlspecialchars(number_format($order['TotalPrice'], 2)) . '<br>
                                                        State: ' . htmlspecialchars($order['state']) . '<br>
                                                    </div>
                                                </div>
                                              </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../ba/footer.php'; ?>
</body>

</html>
