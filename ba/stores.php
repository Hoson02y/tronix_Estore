<section id="services" class="services">

    <div class="container" data-aos="fade-up">

        <header class="section-header">
            <h2>Check <span>Out</span> these Stores</h2>
        </header>

        <div class="row gy-4">

            <?php
            // Assuming $conn is your database connection
            $stmt = $conn->query("SELECT `id`, `id_user`, `phoneCompany`, `address`, `locationCompany`, `store_name`, `emailCompany`, `img_path`, `descriptionCompany`, `linkMap` FROM `suppliers`");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-box orange">
                        <!-- Adjust image size -->
                        <img src="<?php echo $row['img_path']; ?>" class="img-fluid" alt="" style="max-width: 100%; height: auto;">
                        <br>
                        <h3 style="font-size: 1.5rem;"><?php echo $row['store_name']; ?></h3>
                        <br>
                        <p>Ranked <?php echo $row['id']; ?> store in Palestine</p>
                        <br>
                        <!-- Adjust text size -->
                        <p style="font-size: 1rem;"><?php echo $row['descriptionCompany']; ?></p>
                        <br><br>
                        <a href="<?php echo $row['linkMap']; ?>" class="read-more"><span>Visit linkMap Store</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            <?php } ?>

        </div>

    </div>

</section>
