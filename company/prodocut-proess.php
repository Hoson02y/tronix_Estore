<?php
session_start(); 
require_once '../ba/DBconn.php'; 

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'] ?? 0;

    if ($_POST['brand'] === 'other' && !empty($_POST['otherBrand'])) {
        $brand = $_POST['otherBrand'];
    } else {
        $brand = $_POST['brand'];
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'customer/uploads/';
        $uploadDir1 = 'company/uploads/';
        $imageFileName = basename($_FILES["image"]["name"]);
        $targetFile = $uploadDir . $imageFileName;
        $targetFile1 = $uploadDir1 . $imageFileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Ensure the upload directories exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!is_dir($uploadDir1)) {
            mkdir($uploadDir1, 0777, true);
        }

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Check file size (5MB maximum)
            if ($_FILES["image"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                exit();
            }

            // Allow certain file formats
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if(!in_array($imageFileType, $allowedTypes)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                exit();
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Copy the file to the second directory
                copy($targetFile, $targetFile1);

                try {
                    $sql = "INSERT INTO products (name, description, price, quantity, image_path, brand) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(1, $name);
                    $stmt->bindParam(2, $description);
                    $stmt->bindParam(3, $price);
                    $stmt->bindParam(4, $quantity);
                    $stmt->bindParam(5, $targetFile);
                    $stmt->bindParam(6, $brand);

                    $stmt->execute();
                    $_SESSION['message'] = "New product added successfully!";
                    header('Location: emp.php');
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error uploading file";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file uploaded or error in uploading";
    }

    $stmt = null;
    $conn = null;
}
?>
