<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../ba/head.php' ?>
<body>
  <?php include 'ba/header.php' ?>
  <br>
  <br>
  <br>
  <?php 
  // Check if there is an error in fetching the token, and display the error message
  if(isset($token["error"])) { ?>
    <div><?=print_r($token);?></div>
  <?php } ?>

<!-- Registration 5 - Bootstrap Brain Component -->
<section class="p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="card border-light-subtle shadow-sm" >
      <div class="row g-0" >
      <div class="col-12 col-md-6 gradient-orange-bg">
          <div class="d-flex align-items-center justify-content-center h-100" >
            <div class="col-10 col-xl-8 py-3">
              <img class="img-fluid rounded mb-4" loading="lazy" src="./assets/img/logo4.png" width="245" height="80" alt="BootstrapBrain Logo">
              <hr class="border-primary-subtle mb-4">
              <h2 class="h1 mb-4">Your gateway to cutting-edge technology - Tronix.</h2>
                                <p class="lead m-0">Innovative electronics, just a click away </p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-5">
                  <h2 class="h3">Registration</h2>
                  <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                </div>
              </div>
            </div>
            <form action="Registration_Customer.php" method="post">
    <div class="row gy-3 gy-md-4 overflow-hidden">
        
        <div class="col-12">
            <label for="username" class="form-label">Username: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
        </div>
        <div class="col-12">
            <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
        </div>
        <div class="col-12">
            <label for="phone" class="form-label">Phone: <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="059000000" required>
        </div>
        <div class="col-12">
            <label for="password" class="form-label">Password: <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="password" id="password" value="" required>
        </div>
        <div class="col-12">
            <label for="confirm_password" class="form-label">Confirm Password: <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="" required>
        </div>
        <div class="col-12">
            <label for="location" class="form-label">Location: <span class="text-danger">*</span></label>
            <select class="form-select" name="location" id="location" required>
                <option value="">Select a location</option>
                <option value="Jerusalem">Jerusalem</option>
                <option value="Bethlehem">Bethlehem</option>
                <option value="Beersheba">Beersheba</option>
                <option value="Nablus">Nablus</option>
                <option value="Gaza">Gaza</option>
                <option value="Tiberius">Tiberius</option>
                <option value="Besan">Besan</option>
                <option value="Jaffa">Jaffa</option>
                <option value="Acre">Acre</option>
                <option value="Hebron">Hebron</option>
                <option value="Safed">Safed</option>
                <option value="Lod">Lod</option>
                <option value="Nazareth">Nazareth</option>
                <option value="ramallah">Ramallah</option>
                <option value="Ramla">Ramla</option>
                <option value="Jericho">Jericho</option>
                <option value="Haifa">Haifa</option>
                <option value="Embryo">Embryo</option>
                <option value="onther">onther city</option>


            </select>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                <label class="form-check-label text-secondary" for="iAgree">
                    I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                </label>
            </div>
        </div>
        <div class="col-12">
            <div class="d-grid">
                <button type="submit" name="submit" class="btn bsb-btn-xl btn-primary" style="background-color: #ff7f11;">Sign up</button>
            </div>
        </div>
    </div>
</form>

            <div class="row">
              <div class="col-12">
                <hr class="mt-5 mb-4 border-secondary-subtle">
                <p class="m-0 text-secondary text-center">Already have an account? <a href="../login-cust.php" class="link-primary text-decoration-none">Sign in</a></p>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
               
                      <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


</body>

</html>
<?php include 'ba/footer.php' ?>