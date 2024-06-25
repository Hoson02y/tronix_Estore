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
include '../ba/header-cust.php';
include "../ba/DBconn.php"; // Including the file for database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Listings</title>
    <link rel="stylesheet" href="css/style.css">
    <?php include '../ba/head.php'; ?>
</head>
<body>
<section id="services" class="services">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <h2>Check <span>Out</span> these Stores</h2>
        </header>
        <div class="row gy-4">
            
            <?php
            
            try {
                $stmt = $conn->prepare("SELECT id, store_name, locationCompany, address, descriptionCompany, img_path FROM suppliers ORDER BY id ASC");
                $stmt->execute();
            
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='col-lg-4 col-md-6' data-aos='fade-up' data-aos-delay='300'>";
                    echo "<div class='service-box orange'>";
                    echo "<img src='" . htmlspecialchars($row['img_path']) . "' class='img-fluid' alt='" . htmlspecialchars($row['store_name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['store_name']) . "</h3>";
                    echo "<p>Location: " . htmlspecialchars($row['locationCompany']) . "</p>";
                    echo "<p>Address: " . htmlspecialchars($row['address']) . "</p>";
                    echo "<p>Description: " . htmlspecialchars($row['descriptionCompany']) . "</p>";
                    echo "<a href='#' class='read-more'><span>Visit Store</span> <i class='bi bi-arrow-right'></i></a>";
                    echo "</div>";
                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
            
            ?>
            
        </div>
    </div>
</section>
<?php include '../ba/footer.php'; ?>
</body>
</html>
