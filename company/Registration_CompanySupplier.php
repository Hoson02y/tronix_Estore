<?php
session_start();
include_once '../ba/DBconn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize and validate form inputs
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $location = sanitize_input($_POST['location']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);

    // New Fields
    $phoneCompany = sanitize_input($_POST['phoneCompany']);
    $address = sanitize_input($_POST['address']);
    $locationCompany = sanitize_input($_POST['locationCompany']);
    $store_name = sanitize_input($_POST['store_name']);
    $emailCompany = sanitize_input($_POST['emailCompany']);
    $descriptionCompany = sanitize_input($_POST['descriptionCompany']);
    $linkMap = sanitize_input($_POST['linkMap']);

    // File upload handling
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["img_path"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . '.' . $imageFileType;
    $uploadOk = 1;

    // Validate file upload
    $check = getimagesize($_FILES["img_path"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        $error_message = "File is not an image.";
    }

    if (file_exists($target_file)) {
        $uploadOk = 0;
        $error_message = "Sorry, file already exists.";
    }

    if ($_FILES["img_path"]["size"] > 500000) {
        $uploadOk = 0;
        $error_message = "Sorry, your file is too large.";
    }

    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $uploadOk = 0;
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. $error_message";
    } else {
        if (move_uploaded_file($_FILES["img_path"]["tmp_name"], $target_file)) {
            if ($password !== $confirm_password) {
                echo "Passwords do not match!";
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            try {
                $conn->beginTransaction();

                $sql_user = "INSERT INTO users (username, email, phone, location, password, user_type, is_active) VALUES (:username, :email, :phone, :location, :password, :user_type, :is_active)";
                $stmt_user = $conn->prepare($sql_user);
                $stmt_user->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':location' => $location,
                    ':password' => $hashed_password,
                    ':user_type' => 'company',
                    ':is_active' => 0
                ]);

                $user_id = $conn->lastInsertId();

                $sql_supplier = "INSERT INTO suppliers (id_user, phoneCompany, address, locationCompany, store_name, emailCompany, img_path, descriptionCompany, linkMap) 
                VALUES (:user_id, :phoneCompany, :address, :locationCompany, :store_name, :emailCompany, :img_path, :descriptionCompany, :linkMap)";
                $stmt_supplier = $conn->prepare($sql_supplier);
                $stmt_supplier->execute([
                    ':user_id' => $user_id,
                    ':phoneCompany' => $phoneCompany,
                    ':address' => $address,
                    ':locationCompany' => $locationCompany,
                    ':store_name' => $store_name,
                    ':emailCompany' => $emailCompany,
                    ':img_path' => $target_file,
                    ':descriptionCompany' => $descriptionCompany,
                    ':linkMap' => $linkMap
                ]);

                $sql_update = "INSERT INTO requestanaccount (id_users) VALUES (:user_id)";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->execute([
                    ':user_id' => $user_id
                ]);

                $conn->commit();
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: ../index.php");
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
$conn = null;
?>
