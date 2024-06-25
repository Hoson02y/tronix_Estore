<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'admin') {
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
    <?php include '../ba/header-empl.php'; ?>
    <section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <div class="container mt-5">
        <h1>Welcome, <?php echo isset($_SESSION['storeName']) ? htmlspecialchars($_SESSION['storeName']) : "Admin"; ?>!</h1>
      

        <div class="row">
            <br>
            
            <div class="col-md-4">
                <br>
                <div class="card text-white theme-bg-color mb-3">
                    <div class="card-header">Companies requests</div>
                    <div class="card-body">
                        <h5 class="card-title">requests</h5>
                        <p class="card-text">Check requests </p>
                        <a href="companiesRequests.php" class="btn btn-theme"> Go to Companies requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <br>
                <div class="card text-white bg-librown mb-3">
                    <div class="card-header">Companies accounts</div>
                    <div class="card-body">
                        <h5 class="card-title">accounts</h5>
                        <p class="card-text">View accounts .</p>
                        <a href="companiesAccounts.php" class="btn btn-theme"> Go to Companies accounts</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <br>
                <div class="card bg-gray mb-3">
                    <div class="card-header theme-text-color">Customer accounts</div>
                    <div class="card-body">
                        <h5 class="card-title theme-text-color">Manage Customer accounts</h5>
                        <p class="card-text theme-text-color">Customer</p>
                        <a href="custemersAccounts.php" class="btn btn-theme"> Go to Customer accounts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="card text-white bg-librown mb-3">
                    <div class="card-header">Admin reports</div>
                    <div class="card-body">
                        <h5 class="card-title">Manage Admin reports</h5>
                        <p class="card-text">Admin.</p>
                        <a href="adminReports.php" class="btn btn-theme"> Go to Admin reports</a>
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