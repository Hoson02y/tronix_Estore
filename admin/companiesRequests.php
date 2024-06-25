<?php
session_start();
include '../ba/head.php';
require_once '../ba/DBconn.php';

// التحقق من تسجيل الدخول وصلاحيات المستخدم
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_form.php");
    exit();
}

// تحديث حالة الحسابات أو حذف الطلب إذا تم إرسال طلب POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete_request') {
        $userId = $_POST['user_id'];

        // حذف الطلب من جدول requestanaccount 
        $sql = "DELETE FROM requestanaccount WHERE id_users = (SELECT id_user FROM suppliers WHERE id_user = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();

        // حذف من جدول suppliers
        $sql = "DELETE FROM suppliers WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();

        // حذف من جدول users
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        
    } else {
        $userId = $_POST['user_id'];
        $newStatus = $_POST['new_status'];

        // تحديث قيمة is_active في قاعدة البيانات
        $sql = "UPDATE users SET is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $newStatus, PDO::PARAM_INT);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// استعلام لاسترجاع بيانات الشركات
$sql = "
SELECT 
    u.id, u.username, u.email, u.location, u.is_active, 
    s.phoneCompany, s.address, s.locationCompany, s.store_name, 
    s.emailCompany, s.img_path, s.descriptionCompany, s.linkMap
FROM 
    suppliers s
JOIN 
    requestanaccount r ON r.id_users = s.id_user
JOIN 
    users u ON  u.id = s.id_user
WHERE 
    u.user_type = 'company' 
";
$result = $conn->query($sql);

$companies = [];
if ($result->rowCount() > 0) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $companies[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies Requests</title>
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

        function deleteRequest(userId) {
            if (confirm('Are you sure you want to delete this request?')) {
                var formData = new FormData();
                formData.append('user_id', userId);
                formData.append('action', 'delete_request');

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
        }
    </script>
</head>
<body>
    <?php include '../ba/header-empl.php'; ?>
    <div class="container mt-5">
        <h1>Companies Requests</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Location</th>
                    <th>Store Name</th>
                    <th>Email Company</th>
                    <th>Description</th>
                    <th>Map</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?php echo htmlspecialchars($company['username']); ?></td>
                    <td><?php echo htmlspecialchars($company['email']); ?></td>
                    <td><?php echo htmlspecialchars($company['phoneCompany']); ?></td>
                    <td><?php echo htmlspecialchars($company['address']); ?></td>
                    <td><?php echo htmlspecialchars($company['locationCompany']); ?></td>
                    <td><?php echo htmlspecialchars($company['store_name']); ?></td>
                    <td><?php echo htmlspecialchars($company['emailCompany']); ?></td>
                    <td><?php echo htmlspecialchars($company['descriptionCompany']); ?></td>
                    <td><?php echo htmlspecialchars($company['linkMap']); ?></td>
                    <td>
                        <?php if ($company['is_active'] == 1): ?>
                            <button class="btn btn-success status-button" onclick="toggleStatus(<?php echo $company['id']; ?>, 1)">Active</button>
                        <?php else: ?>
                            <button class="btn btn-danger status-button" onclick="toggleStatus(<?php echo $company['id']; ?>, 0)">Disabled</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-danger delete-button" onclick="deleteRequest(<?php echo $company['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include '../ba/footer.php'; ?>
</body>
</html>
