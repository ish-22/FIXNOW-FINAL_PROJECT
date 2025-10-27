<?php include './partials/header.php'; ?>


<!-- Chatbot Container -->
<div id="chatbot-container"
    style="position: fixed; bottom: 20px; right: 20px; display: none; width: 300px; height: 400px; background: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index: 1000;">
    <div id="chatbot-header"
        style="background: #4CAF50; color: white; padding: 10px; text-align: center; font-size: 16px;">Fixnow Assistant
    </div>
    <div id="chatbot-messages" style="height: 300px; overflow-y: auto; padding: 10px;"></div>
    <input type="text" id="chatbot-input" placeholder="Ask me anything..."
        style="width: 100%; padding: 10px; border: none; border-top: 1px solid #ccc; outline: none;" />
</div>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle"
    style="position: fixed; bottom: 20px; right: 20px; background: #4CAF50; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 20px; cursor: pointer; z-index: 1001;">💬</button>

<script>
// Toggle chatbot visibility
const toggleBtn = document.getElementById('chatbot-toggle');
const chatbotContainer = document.getElementById('chatbot-container');
toggleBtn.addEventListener('click', () => {
    chatbotContainer.style.display = chatbotContainer.style.display === 'none' ? 'block' : 'none';
});

// Handle user input and API call
const input = document.getElementById('chatbot-input');
const messages = document.getElementById('chatbot-messages');

input.addEventListener('keypress', async (e) => {
    if (e.key === 'Enter' && input.value.trim()) {
        const userMsg = document.createElement('div');
        userMsg.textContent = 'You: ' + input.value;
        userMsg.style.marginBottom = '10px';
        messages.appendChild(userMsg);
        messages.scrollTop = messages.scrollHeight;

        const response = await getChatbotResponse(input.value);
        const botMsg = document.createElement('div');
        botMsg.textContent = 'Fixnow Assistant: ' + response;
        botMsg.style.marginBottom = '10px';
        messages.appendChild(botMsg);
        messages.scrollTop = messages.scrollHeight;

        input.value = '';
    }
});

async function getChatbotResponse(message) {
    
    // Convert message to lowercase for easier matching
    const lowerMessage = message.toLowerCase();

    // Hardcoded responses based on your website content
    if (lowerMessage.includes('how to find services') || lowerMessage.includes('find service') || lowerMessage
        .includes('how do i find')) {
        return 'You can find services on Fixnow by signing up on our website, then searching for verified professionals near you using our easy-to-navigate platform.';
    }
    if (lowerMessage.includes('what is fixnow') || lowerMessage.includes('about fixnow')) {
        return 'Fixnow connects customers with service providers in Sri Lanka through a website. It offers easy service search, pair booking pricing, verified professionals, and seamless communication.';
    }
    if (lowerMessage.includes('pricing') || lowerMessage.includes('cost') || lowerMessage.includes('how much')) {
        return 'Fixnow offers transparent pricing with no hidden fees. Costs vary by service provider—check their profiles on our website or app for details.';
    }
    if (lowerMessage.includes('sign up') || lowerMessage.includes('create account') || lowerMessage.includes(
            'how to join')) {
        return 'To sign up, click "Create Your Account" on the Fixnow website or download our Android/iOS app and follow the registration steps.';
    }
    if (lowerMessage.includes('app') || lowerMessage.includes('mobile')) {
        return 'Fixnow has Android and iOS apps available. Download them to access professional services on the go with a user-friendly interface.';
    }
    if (lowerMessage.includes('professionals') || lowerMessage.includes('workers') || lowerMessage.includes(
            'technicians')) {
        return 'Fixnow works with verified professionals who deliver high-quality, punctual services. You can find them through our website or apps.';
    }

    // Fallback to API if no match is found
    const context = `
      You are Fixnow Assistant, helping users with the Fixnow platform. Fixnow connects customers with service providers in Sri Lanka through a website, Android app, and iOS app. It offers easy service search, transparent pricing, verified professionals, and seamless communication. Users can sign up to find jobs or hire professionals.
    `;
    const prompt = `${context} User asked: ${message}. Answer as Fixnow Assistant`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`, // ✅ Fixed syntax
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                inputs: prompt
            }),
        });

        const data = await response.json();
        console.log('Raw API Response:', data); // Debug output

        if (data.error) {
            return `API Error: ${data.error}`; // ✅ Fixed syntax
        }

        if (Array.isArray(data) && data.length > 0) {
            return data[0].generated_text || 'Sorry, I couldn’t understand that. Ask me about Fixnow services!';
        }

        return data.generated_text ||
            'Hi! I can help with Fixnow. Ask me about finding services, pricing, or signing up!';
    } catch (error) {
        console.error('Fetch Error:', error);
        return 'Oops, something went wrong! Try asking about Fixnow services.';
    }
}
</script>




<!-- 
        - #HERO
      -->

<section class="section hero" id="home">
    <div class="container">

        <figure class="hero-banner">
            <img src="./assets/images/hero-banner2.png" width="804" height="693" loading="lazy" alt="hero banner"
                class="w-100">
        </figure>

        <div class="hero-content">

            <h2 class="h1 hero-title">Seamless Solutions for Modern Connections</h2>

            <p class="section-text">
                FixNow seamlessly bridges the gap between customers and service
                providers by offering a reliable efficient platform.
                With a focus on professionalism and convenience, FixNow ensures easy
                access to trusted experts, secure transactions, and a hassle-free service
                booking experience, redefining the way services are delivered in Sri Lanka.<br><br>
            </p>

            <button type="button" class="btn btn-primary" onclick="location.href='signup.php';">Create Your
                Account</button>


        </div>

    </div>
</section>

<!-- 
        - #SERVICE
      -->

<section style="padding: 50px 0;">
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
                        <img src="./assets/images/service.jpeg" style="width: 100%; height: 200px; object-fit: cover;"
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
                        <img src="./assets/images/best offer.jpg" style="width: 100%; height: 200px; object-fit: cover;"
                            alt="Career Counseling">
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
                        <img src="./assets/images/life hack.jpg" style="width: 100%; height: 200px; object-fit: cover;"
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
                Your FixNow Digital Platform for Reliable Services, Streamlined Communication, and a Professional
                Marketplace That Works for Everyone
            </p>

            <div class="features-actions" style="display: flex; justify-content: center; gap: 15px;">
                <a href="signin.php" class="btn btn-primary" id="find-job-btn"
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
                            <a href="#" class="card-title">Transparent Booking Price</a>
                        </h3>

                        <p class="card-text">
                            Enjoy transparent, standardized Booking prices for all services.
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
            "Join FixNow today to unlock your potential and achieve success with reliable service providers"
            <br>
            <a href="signup.php" class="btn-link">
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

        <a href="contact.php" class="btn btn-primary">Contact Us Now</a>

    </div>
</section>

</article>
</main>



<?php include './partials/footer.php'; ?>