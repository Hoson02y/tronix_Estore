<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username'])|| $_SESSION['user_type'] !='customer') {
    header("Location: login_form.php");
    exit();
}
// <?php
    // session_start();
 
    include '../ba/DBconn.php';
?>
<?php include '../ba/header-cust.php' ?>
<style>
        .input-group {
            margin-bottom: 1rem;
        }
        .form-check {
            margin-bottom: 1rem;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card {
            height: 100%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1.2em;
        }
        .card-text {
            font-size: 0.9em;
        }
        .btn-primary {
            margin-top: 1rem;
        }
    </style>
<section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <div class="container mt-4">
        <h1>Search for Products</h1>
        <form action="" method="GET" class="d-flex align-items-end">
            <div class="input-group me-3">
            <label for="query" class="form-label">Search Query:</label>
            <input type="text" class="form-control" id="query" name="query" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>"><br><br>
            </div>
            <div class="input-group me-3">
                <span class="input-group-text">Min Price:</span>
                <input type="number" class="form-control" id="minPrice" name="minPrice">
            </div>
            <div class="input-group me-3">
                <span class="input-group-text">Max Price:</span>
                <input type="number" class="form-control" id="maxPrice" name="maxPrice">
            </div>
            <div class="input-group me-3">
                <span class="input-group-text">Brand:</span>
                <input type="text" class="form-control" id="brand" name="brand">
            </div>
            <div class="form-check me-3">
                <input type="checkbox" class="form-check-input" id="new" name="new">
                <label class="form-check-label" for="new">New Only</label>
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>
    </div>
    <style>
        .card-img-top {
            width: 100%;
            height: 200px; /* ضبط ارتفاع الصورة */
            object-fit: cover; /* لجعل الصورة تتناسب مع الحجم بدون تشويه */
        }
        .card {
            height: 100%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1.2em;
        }
        .card-text {
            font-size: 0.9em;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <?php
        if (!empty($_GET['query'])) {
            $query = $_GET['query'];
            $sql = "SELECT * FROM products WHERE name LIKE :searchQuery OR description LIKE :searchQuery ORDER BY CASE WHEN name LIKE :exactStart THEN 0 ELSE 1 END, name";
            $stmt = $conn->prepare($sql);

            $searchQuery = '%' . $query . '%';
            $exactStart = $query . '%';
            $stmt->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(':exactStart', $exactStart, PDO::PARAM_STR);
            $stmt->execute();

            echo "<h2>Results</h2>";
            echo "<p>Check each product page for other buying options.</p>";

            if ($stmt->rowCount() > 0) {
                $brands = array();

                echo "<div class='row'>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $img = htmlspecialchars($row['image_path']);
                    $name = htmlspecialchars($row['name']);
                    $brand = htmlspecialchars($row['brand']);
                    $desc = htmlspecialchars($row['description']);
                    $productId = $row['id']; // Assuming 'id' is the primary key column in the 'products' table
                    echo "<div class='col-md-4 mb-3'>";
                    echo "<div class='card'>";
                    echo "<a href='product.php?id=$productId'><img src='$img' alt='Product Image' class='card-img-top'></a>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>$name</h5>";
                    echo "<p class='card-text'><strong>Brand:</strong> $brand</p>";
                    echo "<p class='card-text'>$desc</p>";
                    echo "<a href='addToCart.php?id=$productId' class='btn btn-success'>Add to Cart</a>"; // Adding Add to Cart button
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";

                    // Collect unique brands
                    if (!in_array($brand, $brands)) {
                        $brands[] = $brand;
                    }
                }
                echo "</div>";

                // Display brand filter options
                echo "<div class='mt-4'>";
                echo "<h5>Filter by Brand:</h5>";
                echo "<div class='form-check form-check-inline'>";
                echo "<input type='checkbox' class='form-check-input' id='allBrands' name='allBrands' value='' checked>";
                echo "<label class='form-check-label' for='allBrands'>All</label>";
                echo "</div>";
                foreach ($brands as $brand) {
                    echo "<div class='form-check form-check-inline'>";
                    echo "<input type='checkbox' class='form-check-input' id='brandFilter' name='brandFilter' value='$brand'>";
                    echo "<label class='form-check-label' for='brandFilter'>$brand</label>";
                }
                echo "</div>";
            } else {
                echo "<p>No products found.</p>";
            }
        } else {
            echo "<p>Please enter a search query.</p>";
        }
        ?>
    </div>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include '../ba/head.php'
    
    ?>
    <title>Search Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .input-group-text,
        .form-control {
            padding: 0.375rem 0.75rem;
            height: 38px;
        }

        #query {
            width: 300px;
            font-size: 1.1rem;
        }

        .form-control {
            max-width: 140px;
        }
    </style>
    </head>
    </html>
    </div>
    </section>
    
</head>
<body>
    



    <?php include '../ba/footer.php' ?>
</body>
</html>
