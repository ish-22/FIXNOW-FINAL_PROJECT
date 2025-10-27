<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | FixNow</title>

    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/privacy_policy.css">
</head>

<body>
    <header>
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us. We are dedicated to keeping your personal information safe and secure.</p>
        <div class="wave"></div>
    </header>

    <div class="container">
    
        <div class="section">
            <h2>Introduction</h2>
            <p>At FixNow, we are committed to protecting your privacy. This Privacy Policy outlines the types of information we collect, how we use it, and how we ensure its security.</p>
        </div>

        <!-- Information Collection Section -->
        <div class="section">
            <h2>Information We Collect</h2>
            <p>We collect the following types of information to enhance your experience on FixNow:</p>
            <ul>
                <li>Personal Information: Name, email address, phone number, location, etc.</li>
                <li>Usage Data: Device information, browser details, IP address, etc.</li>
                <li>Cookies and Tracking Technologies: To personalize your experience and improve functionality.</li>
            </ul>
        </div>

        <!-- Accordion Section -->
        <div class="accordion">
            <!-- Use of Information -->
            <div class="accordion-item">
                <h3>
                    How We Use Your Information
                    <span class="accordion-icon">▼</span>
                </h3>
                <p>
                    We use your data to offer personalized services, enhance user experience, and improve the platform's overall performance.
                </p>
            </div>

            <!-- Sharing Information -->
            <div class="accordion-item">
                <h3>
                    Sharing Your Information
                    <span class="accordion-icon">▼</span>
                </h3>
                <p>
                    We do not sell or share your personal data with third parties. However, we may share your information with trusted partners for operational purposes and analytics.
                </p>
            </div>

            <!-- Data Security -->
            <div class="accordion-item">
                <h3>
                    Data Security
                    <span class="accordion-icon">▼</span>
                </h3>
                <p>
                    We use standard industry practices such as encryption and secure servers to protect your data. However, no system can be 100% secure.
                </p>
            </div>

            <!-- User Rights -->
            <div class="accordion-item">
                <h3>
                    Your Rights
                    <span class="accordion-icon">▼</span>
                </h3>
                <p>
                    You have the right to access, update, and delete your personal information. If you have any concerns about your data, feel free to contact us.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Accordion Functionality
        const accordionItems = document.querySelectorAll('.accordion-item h3');

        accordionItems.forEach((item) => {
            item.addEventListener('click', () => {
                const content = item.nextElementSibling;
                const icon = item.querySelector('.accordion-icon');

                if (content.style.display === "block") {
                    content.style.display = "none";
                    icon.classList.remove('rotate');
                } else {
                    content.style.display = "block";
                    icon.classList.add('rotate');
                }
            });
        });
    </script>
</body>

</html>
