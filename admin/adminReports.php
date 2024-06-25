<?php
session_start();
include '../ba/head.php';
require_once '../ba/DBconn.php';

// التحقق من تسجيل الدخول وصلاحيات المستخدم
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_form.php");
    exit();
}

// استعلام لاسترجاع بيانات الطلبيات
$sql = "
SELECT 
    s.store_name, 
    COUNT(o.id) as total_orders, 
    SUM(oi.quantity) as total_items, 
    SUM(oi.TotalPrice * oi.quantity) as total_revenue
FROM 
    orders o
JOIN 
    order_items oi ON o.id = oi.order_id
JOIN 
    products p ON oi.product_id = p.id
JOIN 
    suppliers s ON p.company_id = s.id_user
GROUP BY 
    s.store_name
";
$result = $conn->query($sql);

$reports = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $reports[] = $row;
    }
}

// إغلاق اتصال PDO
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .btn-theme {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php include '../ba/header-empl.php'; ?>
    <div class="container mt-5">
        <h1>Admin Reports</h1>
        <canvas id="ordersChart" width="400" height="200"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($reports, 'store_name')); ?>,
                datasets: [{
                    label: 'Total Orders',
                    data: <?php echo json_encode(array_column($reports, 'total_orders')); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Total Items',
                    data: <?php echo json_encode(array_column($reports, 'total_items')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Total Revenue',
                    data: <?php echo json_encode(array_column($reports, 'total_revenue')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <?php include '../ba/footer.php'; ?>
</body>
</html>
