<?php
include './partials/headerwithoutcss.php'; ?>
<link rel="stylesheet" href="./assets/css/custom.css">



<div
    style="max-width: 600px; margin: 120px auto; background-color: white; padding: 50px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

    <h2 style="text-align: center; margin-bottom: 20px;">We Value Your Feedback</h2>

    <form action="submit_feedback.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required
            style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 4px;" />
        <input type="email" name="email" placeholder="Your Email" required
            style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 4px;" />
        <textarea name="message" placeholder="Your Message" required
            style="width: 100%; padding: 12px; margin-bottom: 15px; height: 120px;"></textarea>
        <button type="submit"
            style="width: 100%; padding: 12px; background-color: #2d89ef; color: white; border: none; font-size: 16px; border-radius: 5px; cursor: pointer;">
            Submit Feedback
        </button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php" style="color: #2d89ef; text-decoration: none;">← Back to Home</a>
    </div>
</div>





<?php include './partials/footer.php'; ?>