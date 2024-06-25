<?php
session_start();

// التحقق من تسجيل الدخول وصلاحيات المستخدم
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'company') {
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
// <?php
// session_start();
include '../ba/head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
    </style>
</head>
<body>
    <?php include '../ba/header-emp.php'; ?>
    <section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <div class="container mt-5">
      
        <h1>Welcome, <?php echo isset($user['store_name']) ? htmlspecialchars($user['store_name']) : "Store's company"; ?>!</h1>
        <div class="row">
            <br>
            <div class="col-md-4">
                <br>
                <div class="card bg-gray mb-3">
                    <div class="card-header theme-text-color">Products</div>
                    <div class="card-body">
                        <h5 class="card-title theme-text-color">Manage Your Products</h5>
                        <p class="card-text theme-text-color">Add, edit, or remove products.</p>
                        <a href="emp.php" class="btn btn-theme">Go to Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <br>
                <div class="card text-white theme-bg-color mb-3">
                    <div class="card-header">Orders</div>
                    <div class="card-body">
                        <h5 class="card-title">View Orders</h5>
                        <p class="card-text">Check order status and manage shipping.</p>
                        <a href="view_order.php" class="btn btn-theme">View Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <br>
                <div class="card text-white bg-librown mb-3">
                    <div class="card-header">Statistics</div>
                    <div class="card-body">
                        <h5 class="card-title">Store Insights</h5>
                        <p class="card-text">View detailed analytics and growth metrics.</p>
                        <a href="emp-rep.php" class="btn btn-theme">View Statistics</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </section>
    <?php include '../ba/footer.php'; ?>
</body>
</html>