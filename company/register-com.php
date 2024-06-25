<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'ba/head.php' ?>
</head>

<body>

    <?php include 'ba/header.php' ?>

    <br><br><br>

    <?php if (isset($token["error"])) { ?>
        <div><?= print_r($token); ?></div>
    <?php } ?>

    <!-- Registration Form -->
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6 gradient-orange-bg">
                        <div class="d-flex align-items-center justify-content-center h-100">
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
                                        <h2 class="h3">Registration Of Company </h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                                    </div>
                                </div>
                            </div>

                            <form action="Registration_CompanySupplier.php" method="post" enctype="multipart/form-data">
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
                                        <label for="phone" class="form-label">Phone number of the company owner: <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="0590000000" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="confirm_password" class="form-label">Confirm Password: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                    </div>

                                    <!-- New Fields for Company Supplier -->

                                    <div class="col-12">
                                        <label for="store_name" class="form-label">Store Name: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="store_name" id="store_name" placeholder="Store Name" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="emailCompany" class="form-label">Company Email: <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="emailCompany" id="emailCompany" placeholder="company@example.com" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="img_path" class="form-label">Company Image: <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="img_path" id="img_path" required>
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
                                        <label for="phoneCompany" class="form-label">Company Phone: <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="phoneCompany" id="phoneCompany" placeholder="059000000" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label">Company Address : <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" id="address" placeholder="Street, City" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="locationCompany" class="form-label">Company Location: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="locationCompany" id="locationCompany" placeholder="Ramallah" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="linkMap" class="form-label">Link to the company's website on Google Map: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="linkMap" id="linkMap" placeholder="https://maps.google.com" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="descriptionCompany" class="form-label">Company Description: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="descriptionCompany" id="descriptionCompany" rows="3" required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                                            <label class="form-check-label text-secondary" for="iAgree">
                                                I agree to the
                                                <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" name="submit" class="btn bsb-btn-xl btn-primary" style="background-color: #ff7f11;">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <p class="m-0 text-secondary text-center">Already have an account?
                                        <a href="login-cust.php" class="link-primary text-decoration-none">Sign in</a>
                                    </p>
                                </div>
                            </div>
                             
                            <div class="row mt-4">
                                <div class="col-12">
                                    <p class="mb-0 small">By signing in to your account, you agree with our
                                        <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'ba/footer.php' ?>
</body>

</html>