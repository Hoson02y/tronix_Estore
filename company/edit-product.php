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
include '../ba/header-emp.php' ;

// session_start();
require_once '../ba/DBconn.php';

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
      
        $quantity = $row['quantity'];
        $image_path = $row['image_path'];
    } else {
        $_SESSION['message'] = "Product not found";
        header("location: error.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updated_name = $_POST['name'];
    $updated_description = $_POST['description'];
    $updated_price = $_POST['price'];

    $updated_quantity = $_POST['quantity'];
    $image_updated = false;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_updated = true;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ?";
    $params = [$updated_name, $updated_description, $updated_price,  $updated_quantity];
    if ($image_updated) {
        $sql .= ", image_path = ?";
        $params[] = $target_file; 
    }
    $sql .= " WHERE id = ?";
    $params[] = $id; 
    
    $updateStmt = $conn->prepare($sql);
    $updateStmt->execute($params);

    $_SESSION['message'] = "Product updated successfully!";
    header("location: emp.php");
    exit();
}
?>

  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/style.css">
    <?php include '../ba/head.php' ?>

</head>
<body>
  <br>
  <section class="p-3 p-md-4 p-xl-5">
  <div class="container mt-4" style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">Edit Product</h1>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
    <label for="name" class="form-label"><b>Name:</b></label>
    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description:</label>
    <textarea class="form-control form-control-sm" id="description" name="description" rows="3"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
</div>
<div class="mb-3">
    <label for="price" class="form-label"><b>Price:</b></label>
    <input type="text" class="form-control form-control-sm" id="price" name="price" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>">
</div>

<div class="mb-3">
    <label for="quantity" class="form-label"><b>Quantity:</b></label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" value="<?php echo isset($quantity) ? htmlspecialchars($quantity) : ''; ?>">
</div>

                <button type="submit" class="btn btn-primary btn-sm">Update</button>
            </form>
        </div>
    </div>
    <a href="emp.php" class="btn btn-link btn-sm mt-3">Back to products</a>
</div>

</section> 
</section>
</body>
</html>
<?php  include '../ba/footer.php' ?>
