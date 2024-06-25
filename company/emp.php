<?php
session_start();


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
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>"; 
    unset($_SESSION['message']); 
}
?>


<!DOCTYPE html>
<html lang="en">
<style>
    body { margin-top: 20px; }
    .btn-modal { background-color: #ff7f11; color: white; }
    .table-responsive { margin: 20px 0; }
    .table { font-size: 12px; } 
    .table th, .table td { 
      text-align: center; 
      vertical-align: middle; 
      padding: 8px; 
    }
    img { height: 100px; } 
  </style>
<?php include '../ba/head.php' ?>

<body>

  <?php include '../ba/header-emp.php' ?>


       <br>
       <br>
       <br>
    

<div class="d-flex justify-content-center mt-4 mb-4">
<button type="button" class="btn btn-modal " data-bs-toggle="modal" style="background-color:#ff7f11;color:whitesmoke " data-bs-target="#myModal">
  Add Product
</button>
</div>
<div>
   <header class="section-header">
     <br>
     <p color="gray"> List of <span>Products</span> :</p>
        </header>
        <?php

$companyId = $_SESSION['company_id'];
include_once '../ba/DBconn.php';
try {
  $stmt = $conn->prepare("SELECT id, name, price, image_path FROM products ");
  
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
      echo '<table class="table table-hover">';
      echo '<thead>
              <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Image</th>
            
                  <th>Delete</th>
                  <th>Edit</th>
              </tr>
            </thead>
            <tbody>';
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . htmlspecialchars($row['id']) . '</td>';
          echo '<td>' . htmlspecialchars($row['name']) . '</td>';
          echo '<td>' . htmlspecialchars($row['price']) . ' <br><small style="color: #ff7f11;"> ' .  '</small></td>';
          echo '<td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Product Image"></td>';
          echo '<td><a href="delete-product.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="bi bi-trash" style="color:red;"></i></a></td>';
          echo '<td><a href="edit-product.php?id=' . $row['id'] . '"><i class="bi bi-pencil-square" style="color:blue;"></i></a></td>';
          echo '</tr>';
      }
      echo '</tbody></table>'; 
  } else {
      echo '<p>No products found.</p>'; 
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage(); 
}
$conn = null;
?>
</div>

<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">


    <div class="modal-body">
    <form action="prodocut-proess.php" method="post" enctype="multipart/form-data">
        <div class="mb-3 mt-3">
            <label for="productName" class="form-label"><b>Product Name:</b></label>
            <input type="text" class="form-control" id="productName" placeholder="Enter Product Name" name="name">
        </div>
        <div class="mb-3">
            <label for="productDescription" class="form-label">Description:</label>
            <textarea class="form-control" id="productDescription" rows="3" name="description"></textarea>
        </div>
        <div class="mb-3 mt-3">
            <label for="productPrice" class="form-label"><b>Price:</b></label>
            <input type="text" class="form-control" id="productPrice" placeholder="Enter Price" name="price">
        </div>
       
        <div class="mb-3 mt-3">
            <label for="quantity" class="form-label"><b>Quantity:</b></label>
            <input type="number" class="form-control" id="quantity" placeholder="Enter Quantity" name="quantity">
        </div>
        <div class="mb-3">
            <label for="productImage" class="form-label"><b>Add Product Image:</b></label>
            <input type="file" class="form-control" id="productImage" name="image">
        </div>
        
        <div class="mb-3">
            <label for="brand" class="form-label"><b>Select Brand:</b></label>
            <select class="form-select" id="brand" name="brand">
                <option value="iPhone">iPhone</option>
                <option value="Apple phone">Apple phone</option>
                <option value="phone headphones">Phone Headphones</option>
                <option value="phone accessories">Phone Accessories</option>
                <option value="laptop">Laptop</option>
                <option value="iPad">iPad</option>
                <option value="electronic watch">Electronic Watch</option>
                <option value="other">Other (Please specify)</option>
                          </select>
        </div>
        <button type="submit" class="btn" style="background-color:#ff7f11;color:whitesmoke">Upload</button>
        <div class="mb-3" id="otherBrand" style="display:none;">
            <label for="otherBrandInput" class="form-label"><b>If other, please specify:</b></label>
            <input type="text" class="form-control" id="otherBrandInput" name="otherBrand">
        </div>
    </form>
</div>

      </div>
    </div>
  </div>
  <a href="home-emp.php" class="btn btn-link btn-sm mt-3">Back to home</a>

</div>
<?php include '../ba/footer.php' ?>
</body>

</html>
<script>
    function checkBrand(select) {
        var otherBrand = document.getElementById('otherBrand');
        if(select.value === "other") {
            otherBrand.style.display = 'block';
        } else {
            otherBrand.style.display = 'none';
        }
    }
</script>