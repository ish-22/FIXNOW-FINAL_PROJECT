<?php include 'partials/header.php'; ?>


<!-- 
        - #HERO
      -->


<section class="section hero" id="home">

    <?php

  // Check if the service provider is logged in
  if (isset($_SESSION['vendor_id'])) {
    $service_provider_id = $_SESSION['vendor_id']; 

    // Fetch the latest notification for the service provider
    $notification_query = "SELECT * FROM tbl_notification 
                         WHERE serviceProvider_id = '$service_provider_id' AND status_provider = 'unread'
                         ORDER BY created_at DESC LIMIT 1";
    $notification_result = mysqli_query($conn, $notification_query);

    if (mysqli_num_rows($notification_result) > 0) {
      $notification = mysqli_fetch_assoc($notification_result);
      $service_provider_notification_message = $notification['service_provider_notification_message']; 
      $booking_id = $notification['booking_id']; 

      // Mark the notification as read after checking it
      $update_notification_query = "UPDATE tbl_notification SET status_provider = 'read' WHERE booking_id = '$booking_id' AND serviceProvider_id = '$service_provider_id'";
      mysqli_query($conn, $update_notification_query);
    }
    $provider_query = "SELECT full_name FROM tbl_service_providers WHERE id = '$service_provider_id'";
    $provider_result = mysqli_query($conn, $provider_query);

    if ($provider_result && mysqli_num_rows($provider_result) > 0) {
      $provider_data = mysqli_fetch_assoc($provider_result);
      $service_provider_name = $provider_data['full_name'];
    } else {
      $service_provider_name = "Service Provider"; 
    }
  } else {
    $service_provider_name = "Guest"; 
  }
  ?>

    <?php if (isset($service_provider_notification_message)) { ?>
    <div class="notification-box">
        <p class="notification-message">
            <?php echo $service_provider_notification_message; ?>
            <a href="customer-bookings.php?booking_id=<?php echo $booking_id; ?>" class="check-link">Check</a>
        </p>
    </div>
    <?php } ?>



    <div class="container">
        <figure class="hero-banner">
            <img src="./assets/images/hero-banner2.png" width="804" height="693" loading="lazy" alt="hero banner"
                class="w-100">
        </figure>
        <div class="hero-content">
            <h3 class="Name_customer">Welcome,
                <?php echo htmlspecialchars($service_provider_name, ENT_QUOTES, 'UTF-8'); ?></h3>

            <h2 class="h1 hero-title">Seamless Solutions for Modern Connections</h2>

            <p class="section-text">
                Fixnow bridges the gap between customers and service providers by
                delivering professionalism, transparency, and convenience. Our solution
                includes an Android app, iOS app, and website to ensure seamless access for everyone,
                recognizing the growing reliance on smartphones in today's world.<br><br>
            </p>

            <button type="button" class="btn btn-primary" onclick="location.href='../signin.php'">Create Your
                Account</button>
        </div>

    </div>
</section>

<!-- 
        - #SERVICE
      -->

<section style="padding: 50px 0;" class="section service">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

        <h2 style="font-size: 32px; text-align: center; margin-bottom: 20px;">FIXNOW Solution</h2>

        <p style="font-size: 25px; text-align: center; margin-bottom: 40px;">
            FixNow aims to bridge this gap by creating a digital
            platform that fosters professionalism, transparency, and convenience. The solution includes:
        </p>

        <ul style="list-style: none; padding: 0; display: flex; flex-wrap: wrap; justify-content: space-between;">

            <li style="width: 32%; margin-bottom: 30px;">
                <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; height: 500px;">

                    <figure style="margin: 0;">
                        <img src="../assets/images/service.jpeg" style="width: 100%; height: 200px; object-fit: cover;"
                            alt="Resume Building">
                    </figure>

                    <div style="padding: 20px;">
                        <h3 style="font-size: 24px; margin: 0 0 15px 0;">
                            <a href="services.php" style="text-decoration: none; color: #333;">Services</a>
                        </h3>

                        <p style="font-size: 20px; margin: 0 0 20px 0; line-height: 1.5;">
                            Discover and book reliable service providers through our easy-to-navigate and responsive
                            website.<br><br>
                        </p>

                        <a href="services.php"
                            style="text-decoration: none; color: #0066cc; display: flex; align-items: center;">
                            <span style="margin-right: 10px;">Grab Now</span>
                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </div>

                </div>
            </li>

            <li style="width: 32%; margin-bottom: 30px;">
                <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; height: 500px;">

                    <figure style="margin: 0;">
                        <img src="../assets/images/best offer.jpg"
                            style="width: 100%; height: 200px; object-fit: cover;" alt="Career Counseling">
                    </figure>

                    <div style="padding: 20px;">
                        <h3 style="font-size: 24px; margin: 0 0 15px 0;">
                            <a href="offers.php" style="text-decoration: none; color: #333;">Offers</a>
                        </h3>

                        <p style="font-size: 20px; margin: 0 0 20px 0; line-height: 1.5;">
                            Enjoy our special discounts and deals.
                            Book now our limited-time offers on trusted professionals!<br><br>
                        </p>

                        <a href="offers.php"
                            style="text-decoration: none; color: #0066cc; display: flex; align-items: center;">
                            <span style="margin-right: 10px;">Grab Now</span>
                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </div>

                </div>
            </li>

            <li style="width: 32%; margin-bottom: 30px;">
                <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; height: 500px;">

                    <figure style="margin: 0;">
                        <img src="../assets/images/life hack.jpg" style="width: 100%; height: 200px; object-fit: cover;"
                            alt="Skill Development">
                    </figure>

                    <div style="padding: 20px;">
                        <h3 style="font-size: 24px; margin: 0 0 15px 0;">
                            <a href="dailyHacks.php" style="text-decoration: none; color: #333;">Daily Hacks</a>
                        </h3>

                        <p style="font-size: 20px; margin: 0 0 20px 0; line-height: 1.5;">
                            Explore expert tips and smart solutions to simplify everyday tasks. FixNow brings you
                            practical advice to make life easier!
                        </p>

                        <a href="dailyHacks.php"
                            style="text-decoration: none; color: #0066cc; display: flex; align-items: center;">
                            <span style="margin-right: 10px;">Grab Now</span>
                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </div>

                </div>
            </li>

        </ul>

    </div>
</section>
<!-- 
        - #FEATURES
      -->

<section class="section features" id="features" style="padding: 50px 0; background-color: #f9f9f9; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

        <div class="features-content" style="margin-bottom: 30px;">

            <h2 class="h2 section-title" style="font-size: 5.5rem; margin-bottom: 20px; color: #333;">
                Reliable Solutions, Anytime, Anywhere
            </h2>

            <p class="section-text" style="font-size: 2.1rem; color: #555; line-height: 1.6; margin-bottom: 30px;">
                Your Go-To Digital Platform for Reliable Services, Streamlined Communication, and a Professional
                Marketplace That Works for Everyone
            </p>

            <div class="features-actions" style="display: flex; justify-content: center; gap: 15px;">
                <a href="../signup.php" class="btn btn-primary" id="find-job-btn"
                    style="padding: 20px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; font-size: 2.5rem; border-radius: 5px;">
                    Find a Job
                </a>
                <a href="vendors.php" class="btn btn-secondary" id="find-employee-btn"
                    style="padding: 20px 20px; background-color: #007BFF; color: #fff; text-decoration: none; font-size: 2.5rem; border-radius: 5px;">
                    Find an Employee
                </a>
            </div>

        </div>

        <div class="banner-wrapper"
            style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-top: 40px;">

            <figure class="features-banner one" style="flex: 1; max-width: 600px;">
                <img src="./assets/images/features-job-search.png" width="600" height="500" loading="lazy"
                    alt="Job Search" style="width: 100%; height: auto; border-radius: 10px;">
            </figure>

        </div>

    </div>
</section>

<!-- 
        - #ABOUT
      -->

<section class="section about" id="about">
    <div class="container">

        <h2 class="h2 section-title">What We Offer</h2>

        <p class="section-text">
            FixNow transforms the outdated service provider system
            in Sri Lanka by offering reliable, efficient, and customer-focused solutions.
        </p>

        <ul class="about-list">

            <li>
                <div class="about-card about-card-1">

                    <figure class="card-banner">
                        <img src="./assets/images/search.png" width="94" height="94" loading="lazy"
                            alt="Career Guidance">
                    </figure>

                    <div class="card-content">

                        <h3 class="h3">
                            <a href="#" class="card-title">Easy Service Search</a>
                        </h3>

                        <p class="card-text">
                            Quickly locate trusted and professional service providers near you through FixNow's
                            centralized platform.
                        </p>

                    </div>

                </div>
            </li>

            <li>
                <div class="about-card about-card-2">

                    <figure class="card-banner">
                        <img src="./assets/images/price.png" width="94" height="94" loading="lazy"
                            alt="Skills Training">
                    </figure>

                    <div class="card-content">

                        <h3 class="h3">
                            <a href="#" class="card-title">Transparent Pricing</a>
                        </h3>

                        <p class="card-text">
                            Enjoy transparent, standardized pricing with no hidden fees for all services.
                        </p>

                    </div>

                </div>
            </li>

            <li>
                <div class="about-card about-card-3">

                    <figure class="card-banner">
                        <img src="./assets/images/worker.png" width="94" height="94" loading="lazy"
                            alt="Job Opportunities">
                    </figure>

                    <div class="card-content">

                        <h3 class="h3">
                            <a href="#" class="card-title">Verified Professionals</a>
                        </h3>

                        <p class="card-text">
                            Work with vetted professionals who deliver high-quality and punctual services every time.
                        </p>

                    </div>

                </div>
            </li>

            <li>
                <div class="about-card about-card-4">

                    <figure class="card-banner">
                        <img src="./assets/images/communication.png" width="94" height="94" loading="lazy"
                            alt="Personalized Mentorship">
                    </figure>

                    <div class="card-content">

                        <h3 class="h3">
                            <a href="#" class="card-title">Seamless Communication</a>
                        </h3>

                        <p class="card-text">
                            Experience smooth communication with service providers, ensuring clarity and timely updates.
                        </p>

                    </div>

                </div>
            </li>

        </ul>

        <p class="section-text">
            Join us today to unlock your potential and achieve career success.
            <a href="../signup.php" class="btn-link">
                <span class="span">Join Us Now</span>
                <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
        </p>

    </div>
</section>




<section class="section" style="padding: 40px 0; background-color: #f8f8f8; min-height: 600px;">
    <div class="container_siwper" style="max-width: 1200px; margin: 0 auto; overflow: hidden; padding: 0;">

        <h2 class="h2 section-title" style="text-align: center; margin-bottom: 20px;">User Feedback</h2>

        <div class="swiper-container" style="padding: 0">
            <div class="swiper-wrapper">
                <?php
        $result = $conn->query("SELECT name, message, submitted_at FROM tbl_user_feedback ORDER BY submitted_at DESC LIMIT 9");
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
                  echo "<div class='swiper-slide' style='
    background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
    padding: 25px 30px; 
    border-left: 6px solid #4a90e2; 
    border-radius: 12px; 
    box-shadow: 0 8px 15px rgba(74, 144, 226, 0.2);
    height: 280px; 
    display: flex; 
    flex-direction: column; 
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
'>
    <div>
        <strong style='font-size: 20px; color: #1a1a1a; font-weight: 700; letter-spacing: 0.03em;'>" . htmlspecialchars($row['name']) . "</strong><br />
        <small style='color: #6c757d; font-style: italic; font-size: 13px; margin-top: 4px; display: inline-block;'>" . $row['submitted_at'] . "</small>
        <p style='margin-top: 15px; font-size: 15px; color: #333; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 5; -webkit-box-orient: vertical;'>
            " . htmlspecialchars($row['message']) . "
        </p>
    </div>
</div>";

          }
        } else {
          echo "<p style='text-align: center; font-size: 16px; color: #555;'>No feedback submitted yet.</p>";
        }
        ?>
            </div>
        </div>
        <!-- 
        Navigation Buttons -->
        <!-- <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> -->

        <!-- Feedback Button -->
        <div style="text-align: center; margin-top: 60px;">
            <a href="user_feedback.php" class="btn btn-secondary"
                style="padding: 15px 25px; background-color: #2d89ef; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; max-width: 200px; display: inline-block; white-space: nowrap; text-align: center;">
                Give Feedback
            </a>
        </div>

    </div>
</section>








<!-- 
        - #SUPPORT
      -->

<section class="section support" id="contact">
    <div class="container">

        <div class="support-content">
            <h2 class="h2 section-title">24/7 Customer Support</h2>

            <p class="section-text">
                Our team is committed to providing a seamless service
                experience by connecting you with trusted professionals
                for all your needs. Explore our platform for instant bookings,
                ecure transactions, and expert solutions, all in one place with FixNow.
            </p>
        </div>

        <a href="../contact.php" class="btn btn-primary">Contact Us Now</a>

    </div>
</section>

</article>
</main>

<?php include './partials/footer.php'; ?>