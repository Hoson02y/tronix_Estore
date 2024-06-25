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
require_once '../ba/DBconn.php';  


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bindParam(1, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete the product.";
    }
} else {
    $_SESSION['message'] = "Invalid product ID.";
}

header("Location: emp.php");
exit;  
?>
