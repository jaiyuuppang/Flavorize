<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Flavorize</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="about.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>

<body>
    <div class="home">
        <header class="header">
            <div class="logo">
                <img src="./images/logoNoBG.png" alt="Flavorize Logo">
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php" style="color: rgb(253, 57, 8);">About</a></li>
                    <li><a href="foodrecipe.php">Food Recipe</a></li>
                    <li><a href="social.php">Social</a></li>
                </ul>
            </nav>
            <div class="account">
                <button class="loginBtn" id="form-open">Login</button>
            </div>
            <div class="bar">
                <i class="fas fa-bars"></i>
            </div>
        </header>

        <div class="about-us">
            <div class="about-container">
                <h1 class="section-header">About Us</h1>
                <div class="content-wrapper">
                    <div class="about-content">
                        <p>
                            Welcome to Flavorize, your go-to personalized food recommendation web app designed to
                            simplify meal planning and support your wellness journey.
                            With a focus on making nutritious eating easy and accessible, Flavorize helps you create
                            delicious meals based on the ingredients you have and your specific health needs.
                            Our mission is to offer a convenient, user-friendly platform where you can get tailored meal
                            suggestions in just a few clicks.
                        </p>
                        <img src="./images/logoNoBG.png" alt="About Us Image" class="section-image">
                    </div>
                    <!-- Features Section -->
                    <div class="about-features">
                        <div class="feature-item">
                            <h3><span class="checkmark">&#10003;</span> Personalized Meal Recommendations</h3>
                            <p>Get recipe ideas based on the ingredients you already have and your unique dietary needs.
                            </p>
                        </div>
                        <div class="feature-item">
                            <h3><span class="checkmark">&#10003;</span> Health-Focused Suggestions</h3>
                            <p>Our system takes into account any health conditions or dietary goals, ensuring that your
                                meal options align with your wellness objectives.</p>
                        </div>
                        <div class="feature-item">
                            <h3><span class="checkmark">&#10003;</span> Ingredient-Based Planning</h3>
                            <p>Don’t know what to cook? Simply input your available ingredients, and Flavorize will
                                suggest a variety of nutritious recipes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- People Behind Flavorize Section -->
        <div class="developers" id="team-section">
            <h2>THE PEOPLE BEHIND FLAVORIZE</h2>
            <div class="developer-cards">
                <div class="developer-card hidden">
                    <img src="./images/developer1.jpg" alt="Rafael D. De Leon">
                    <h3>Rafael D. De Leon</h3>
                    <p>Project Manager & Lead Developer</p>
                </div>
                <div class="developer-card">
                    <img src="./images/developer2.jpg" alt="Rob Nesta Jaihra C. Maribbay">
                    <h3>Rob Nesta Jaihra C. Maribbay</h3>
                    <p>Front-End Developer</p>
                </div>
                <div class="developer-card">
                    <img src="./images/developer3.png" alt="John Angelo D. Molina">
                    <h3>John Angelo D. Molina</h3>
                    <p>Back-End Developer</p>
                </div>
            </div>
        </div>

        <!-- Our Story Section -->
        <div class="our-story">
            <div class="our-story-container">
                <h2 class="section-header">Our Story</h2>
                <div class="content-wrapper">
                    <div class="our-story-content">
                        <p>
                            Flavorize was born out of a passion for making healthy eating accessible and enjoyable for
                            everyone. Our journey started when we noticed the growing challenges people face in choosing
                            nutritious meals that fit their lifestyle and health needs. Whether it's time constraints,
                            limited ingredients, or dietary restrictions, meal planning can feel overwhelming.
                        </p>
                        <img src="./images/logoNoBG.png" alt="Our Story Image" class="section-image">
                    </div>
                    <p>
                        We set out with a simple mission: to empower individuals to make better food choices with ease.
                        Using cutting-edge technology, we designed Flavorize to deliver personalized meal
                        recommendations based on the ingredients you have and your unique dietary goals. Whether you're
                        managing a health condition or just looking to eat healthier, we are here to help simplify your
                        journey.
                    </p>
                    <p>
                        From our humble beginnings as a group of developers passionate about food and technology,
                        Flavorize has grown into a tool that bridges the gap between convenience and nutrition. We
                        believe that everyone deserves to enjoy delicious, nutritious meals without the stress of
                        planning or compromising on health.
                    </p>
                </div>
            </div>
        </div>
        <div class="form-overlay"></div>
        <div class="form-container" id="form-container">
            <i class="fa-solid fa-xmark form_close" id="form-close"></i>

            <div class="form login-form active">
                <form action="aboutDB.php" method="post">
                    <h2>Login</h2>

                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <i class="fa-solid fa-envelope email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" id="login-password" placeholder="Enter your password"
                            required>
                        <i class="fa-solid fa-lock password"></i>
                        <i class="fa-regular fa-eye-slash pw_hide" onclick="togglePassword('login-password', this)"></i>
                    </div>

                    <div class="option_field">
                        <span class="checkbox">
                            <input type="checkbox" id="check">
                            <label for="check">Remember me</label>
                        </span>
                        <a href="#" class="forgot_pw">Forgot password?</a>
                    </div>
                    <button type="submit" class="loginBtn">Login Now</button>

                    <div class="login_signup">
                        Don't have an account? <a href="#" class="show-signup">Signup</a>
                    </div>
                </form>
            </div>

            <div class="form signup-form">
                <form action="signupDB.php" method="post" enctype="multipart/form-data"
                    onsubmit="return validatePasswords()">
                    <h2>Signup</h2>

                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <i class="fa-solid fa-envelope email"></i>
                    </div>
                    <div class="input_box">
                        <input type="first_name" name="first_name" placeholder="Enter your first name" required>
                        <i class="fa-solid fa-user firstName"></i>
                    </div>
                    <div class="input_box">
                        <input type="last_name" name="last_name" placeholder="Enter your last name" required>
                        <i class="fa-solid fa-user lastName"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" id="signup-create-password" placeholder="Create password"
                            required>
                        <i class="fa-solid fa-lock password"></i>
                        <i class="fa-regular fa-eye-slash pw_hide"
                            onclick="togglePassword('signup-create-password', this)"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" id="signup-confirm-password" placeholder="Confirm password" required>
                        <i class="fa-solid fa-lock password"></i>
                        <i class="fa-regular fa-eye-slash pw_hide"
                            onclick="togglePassword('signup-confirm-password', this)"></i>
                    </div>
                    <!-- Error message for password mismatch -->
                    <div id="password-error" style="color: red; font-size: 14px; margin-top: 10px; display: none;">
                        Confirm password does not match.
                    </div>

                    <div class="input_box">
                        <input type="file" name="profile" accept="image/*" placeholder="Enter your photo" required>
                        <i class="fa-regular fa-image profile"></i>
                    </div>

                    <button type="submit" class="loginBtn">Signup Now</button>

                    <div class="login_signup">
                        Already have an account? <a href="#" class="show-login">Login</a>
                    </div>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="footer-1">
                <div class="logo">
                    <img src="./images/logoNoBG.png" alt="Flavorize Logo">
                </div>
                <p>&copy; 2024 Flavorize. All rights reserved.</p>
            </div>
            <div class="footer-2">
                <h2>Stay Connected</h2>
                <p>Follow us on social media for the latest updates.</p>
            </div>
        </footer>

        <script>
            const bar = document.querySelector('.bar');
            const headerbar = document.querySelector('.headerbar');
            const closeBtn = document.querySelector('.fa-xmark');
            const formContainer = document.querySelector('.form-container');
            const openFormButton = document.querySelector('#form-open');
            const closeFormButton = document.querySelector('.form_close');
            const formOverlay = document.querySelector('.form-overlay');
            const loginForm = document.querySelector('.login-form');
            const signupForm = document.querySelector('.signup-form');
            const showSignup = document.querySelector('.show-signup');
            const showLogin = document.querySelector('.show-login');

            // Function to open the form and show the overlay
            openFormButton.addEventListener('click', () => {
                formContainer.classList.add('active');  // Show form
                formOverlay.classList.add('active');    // Show overlay
            });

            // Function to close the form and hide the overlay
            closeFormButton.addEventListener('click', () => {
                formContainer.classList.remove('active');  // Hide form
                formOverlay.classList.remove('active');    // Hide overlay
            });

            // Close form when clicking on the overlay
            formOverlay.addEventListener('click', () => {
                formContainer.classList.remove('active');  // Hide form
                formOverlay.classList.remove('active');    // Hide overlay
            });

            // Prevent closing when clicking inside the form container itself
            formContainer.addEventListener('click', (e) => {
                e.stopPropagation();  // Prevent click event from propagating to the overlay
            });

            showSignup.addEventListener('click', function (e) {
                e.preventDefault();
                loginForm.style.display = 'none';  // Hide login form
                signupForm.style.display = 'block'; // Show signup form
            });

            showLogin.addEventListener('click', function (e) {
                e.preventDefault();
                signupForm.style.display = 'none'; // Hide signup form
                loginForm.style.display = 'block'; // Show login form
            });

            function validatePasswords() {
                const password = document.getElementById("signup-create-password").value;
                const confirmPassword = document.getElementById("signup-confirm-password").value;
                const errorMessage = document.getElementById("password-error");

                if (password !== confirmPassword) {
                    errorMessage.style.display = "block"; // Show error message
                    return false; // Prevent form submission
                } else {
                    errorMessage.style.display = "none"; // Hide error message
                    return true; // Allow form submission
                }
            }

            function togglePassword(fieldId, icon) {
                const passwordField = document.getElementById(fieldId);

                // Toggle the type attribute between "password" and "text"
                if (passwordField.type === 'password') {
                    passwordField.type = 'text'; // Show password
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    passwordField.type = 'password'; // Hide password
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            }

            bar.addEventListener('click', () => {
                headerbar.style.display = 'flex';
                headerbar.classList.add('active');
            });

            closeBtn.addEventListener('click', () => {
                headerbar.style.display = 'none';
                headerbar.classList.remove('active');
            });

            document.addEventListener("DOMContentLoaded", function () {
                // Apply visible class for animation using class selectors instead of IDs
                document.querySelector('.about-us').classList.add('visible');
                document.querySelector('.our-story').classList.add('visible');

                // Team section members appear one by one
                const teamMembers = document.querySelectorAll('.developer-card');
                teamMembers.forEach((member, index) => {
                    member.style.animationDelay = `${1.2 + (index * 0.3)}s`;  // Add delay for each team member
                    member.classList.add('visible');
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const elements = document.querySelectorAll('.developer-card');

                function isElementInViewport(el) {
                    const rect = el.getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                }

                function handleScroll() {
                    elements.forEach(el => {
                        if (isElementInViewport(el)) {
                            el.classList.add('visible');
                            el.classList.remove('hidden');
                        }
                    });
                }

                // Listen for scroll events
                window.addEventListener('scroll', handleScroll);

                // Initial check
                handleScroll();
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    </body>

</html>