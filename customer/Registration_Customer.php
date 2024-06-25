<?php 
session_start();
include_once '../ba/DBconn.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = 'customer';

    echo "Username: $username<br>";
    echo "Email: $email<br>";
    echo "Phone: $phone<br>";
    echo "Location: $location<br>";
    echo "Password: $password<br>";
    echo "Confirm Password: $confirm_password<br>";
    echo "User Type: $user_type<br>";

    if (!ctype_alpha($username[0])) {
        echo "<script>alert('Username should not start with a number.');</script>";
        echo "<script>window.location.href = 'register-cust.php';</script>";
        exit();
    } else if (empty($email) || empty($phone) || empty($location) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
        echo "<script>window.location.href = 'register-cust.php';</script>";
        exit();
    } else if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
        echo "<script>window.location.href = 'register-cust.php';</script>";
        exit();
    } else {
        try {
            $checkUser = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
            $checkUser->execute([':email' => $email, ':username' => $username]);
            if ($checkUser->rowCount() > 0) {
                echo "<script>alert('Username or Email already in use. Please use another username or email.');</script>";
                echo "<script>window.location.href = 'register-cust.php';</script>";
                exit();
            } else {
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (username, email, phone, location, password, user_type, is_active) VALUES (:username, :email, :phone, :location, :password, :user_type, :is_active)");
                $user_type = ($_SERVER['REQUEST_URI'] === '/register-cust.php') ? 'customer' : 'customer';
                $insert->execute([
                    ":username" => $username,
                    ":email" => $email,
                    ":phone" => $phone,
                    ":location" => $location,
                    ":password" => $passwordHashed,
                    ":user_type" => $user_type,
                    ":is_active" => 1
                ]);
                echo "<script>alert('Registration successful. Redirecting...');</script>";
                $conn->commit();
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: ../index.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>