<?php
session_start();

// التحقق من تسجيل الدخول وصلاحيات المستخدم
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

    session_destroy();

    header("Location: ../login_form.php");
    exit();
}

include '../ba/head.php';
require_once '../ba/DBconn.php';

// تحديث حالة الحسابات إذا تم إرسال طلب POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['new_status'];

    // تحديث قيمة is_active في قاعدة البيانات
    $sql = "UPDATE users SET is_active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $newStatus, PDO::PARAM_INT);
    $stmt->bindParam(2, $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // تم التحديث بنجاح
    } else {
        // خطأ في التحديث
    }
}

// استعلام لاسترجاع بيانات الشركات المفعلة فقط
$sql = "SELECT id, username, email, location, is_active FROM users WHERE user_type = 'company' AND is_active = 1";
$result = $conn->query($sql);

$companies = [];
if ($result->rowCount() > 0) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $companies[] = $row;
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
    <title>Companies Accounts</title>
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

        .btn-theme {
            margin-left: 10px;
        }
    </style>
    <script>
        function toggleStatus(userId, currentStatus) {
            var newStatus = currentStatus == 1 ? 0 : 1; // تغيير القيمة بالشكل المعكوس

            var formData = new FormData();
            formData.append('user_id', userId);
            formData.append('new_status', newStatus);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body>
    <?php include '../ba/header-empl.php'; ?>
    <div class="container mt-5">
        <h1>Companies Accounts</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?php echo htmlspecialchars($company['username']); ?></td>
                    <td><?php echo htmlspecialchars($company['email']); ?></td>
                    <td><?php echo htmlspecialchars($company['location']); ?></td>
                    <td>
                        <?php if ($company['is_active'] == 1): ?>
                            <button class="btn btn-success status-button" onclick="toggleStatus(<?php echo $company['id']; ?>, 1)">Active</button>
                        <?php else: ?>
                            <button class="btn btn-danger status-button" onclick="toggleStatus(<?php echo $company['id']; ?>, 0)">Disabled</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include '../ba/footer.php'; ?>
</body>
</html>
