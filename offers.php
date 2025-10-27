<?php include './partials/headerwithoutcss.php'; ?>
<link rel="stylesheet" href="./assets/css/custom.css">
<link rel="stylesheet" href="./assets/css/offers.css">

<?php

$offer_query = "SELECT * FROM tbl_offer";
$offer_result = mysqli_query($conn, $offer_query);
?>

<section class="hero section" id="hero">
  <div class="offer-container">
    <div class="hero-content">
      <h1 class="h1">Exclusive Offers Just for You</h1>
      <p class="section-text">Discover and redeem amazing deals tailored to meet your needs.<br><br></p>

      <div class="offers-grid">
        <?php
        // Check if any offers are available
        if (mysqli_num_rows($offer_result) > 0) {
            while ($offer = mysqli_fetch_assoc($offer_result)) {
                // Fetch offer details from the row
                $offer_title = $offer['title'];
                $offer_image = $offer['offer_image'];
                $offer_description = $offer['description'];
                $offer_price = $offer['current_price'];
                $discounted_price = $offer['discounted_price'];
                $discount_rate = $offer['discount_rate']; 
                $status = $offer['status'];
                $status_text = ucfirst($status);  
                
           
                $discount_rate = intval($discount_rate);

                echo "
                <div class='offer-box'>
                  <img src='images/offer_images/{$offer_image}' alt='{$offer_title}' class='offer-image' />
                  <div class='offer-details'>
                    <h3 class='offer-title'>{$offer_title}</h3>
                    <p class='offer-description'>{$offer_description}</p>
                    <p class='offer-price'>Rs.{$offer_price}</p>
                    <p class='offer-discounted-price'>Discounted Price: Rs.{$discounted_price}</p>
                    <p class='offer-discount-rate' style='color: red;'>Discount: {$discount_rate}% Off</p> <!-- Discount Rate in Red -->
                    <a href='vendors.php' class='btn btn-primary'>Grab Offer</a>
                  </div>
                </div>
                ";
            }
        } else {
            echo "<p>No offers available at the moment.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</section>

<?php include './partials/footer.php'; ?>
