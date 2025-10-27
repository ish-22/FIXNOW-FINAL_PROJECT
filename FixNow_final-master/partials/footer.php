<footer class="footer" id="services">

    <div class="footer-top section">
        <div class="container">

            <div class="footer-brand">
                <a href="#" class="logo">FIXNOW</a>

                <figure class="footer-img">
                    <img src="./assets/images/footer-img1.png" width="264" height="226" loading="lazy"
                        aria-hidden="true" class="w-100">
                </figure>
            </div>

            <ul class="footer-list">

                <li>
                    <p class="footer-list-title">Quick Links</p>
                </li>

                <li>
                    <a href="about.php" class="footer-link">About Us</a>
                </li>

                <li>
                    <a href="FAQ.php" class="footer-link">FAQ</a>
                </li>

                <li>
                    <a href="services.php" class="footer-link">Services</a>
                </li>

            </ul>

            <ul class="footer-list">

                <li>
                    <p class="footer-list-title">Privacy policies</p>
                </li>

                <li>
                    <a href="terms_of_service.php" class="footer-link">Terms of services</a>
                </li>

                <li>
                    <a href="FAQ.php" class="footer-link">Inquries</a>
                </li>

                <li>
                    <a href="contact.php" class="footer-link">Contact Us</a>
                </li>

            </ul>

            <ul class="footer-list">

                <li>
                    <p class="footer-list-title">Get in Touch</p>
                </li>

                <li class="footer-item">
                    <img src="./assets/images/contact-icon-1.svg" width="16" height="16" loading="lazy"
                        aria-hidden="true">

                    <span class="span">
                        Call Us:
                        <a href="tel:+94123456789" class="footer-item-link">+94 777 456 789</a>
                    </span>
                </li>

                <li class="footer-item">
                    <img src="./assets/images/contact-icon-2.svg" width="16" height="16" loading="lazy"
                        aria-hidden="true">

                    <span class="span">
                        Address:
                        <a href="#" class="footer-item-link">Colombo, Sri Lanka</a>
                    </span>
                </li>

                <li class="footer-item">
                    <img src="./assets/images/contact-icon-3.svg" width="16" height="16" loading="lazy"
                        aria-hidden="true">

                    <span class="span">
                        Mail Us:
                        <a href="mailto:info@sriicareer.com" class="footer-item-link">fixnow@gmail.com</a>
                    </span>
                </li>

            </ul>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">

            <p class="copyright">
                &copy; 2025 <span class="span">FIXNOW</span>. All rights reserved by <a href="#"
                    class="copyright-link">FIXNOW.com</a>
            </p>

            <ul class="footer-bottom-list">

                <li>
                    <a href="privacy_policy.php" class="footer-bottom-link">Privacy Policy</a>
                </li>

                <li>
                    <a href="terms_of_service.php" class="footer-bottom-link">Terms of Service</a>
                </li>

                <li>
                    <a href="contact.php" class="footer-bottom-link">Contact Support</a>
                </li>

            </ul>

        </div>
    </div>

</footer>

<!-- 
  - #BACK TO TOP
-->

<a href="#top" class="back-top-btn" aria-label="Back to top" data-back-top-btn>
    <ion-icon name="chevron-up"></ion-icon>
</a>

<!-- 
  - custom js link
-->
<script src="./assets/js/custom.js" defer></script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<script>
const swiper = new Swiper('.swiper-container', {
    slidesPerView: 4,
    spaceBetween: 20,
    loop: false,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        576: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        1024: {
            slidesPerView: 4,
        }
    }
});
</script>




</body>

</html>