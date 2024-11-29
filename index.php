<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavorize | Home</title>
    <link rel="shortcut icon" href="./images/logoNoBG.png" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="header">
            <div class="headerbar">
                <div class="account">
                    <button class="loginBtn">Login</button>
                </div>
                <div class="nav">
                    <ul>
                        <a href="index.php">
                            <li>Home</li>
                        </a>
                        <a href="about.php">
                            <li>About</li>
                        </a>
                        <a href="foodrecipe.php">
                            <li>Food Menu</li>
                        </a>
                        <a href="social.php">
                            <li>Social</li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="logo">
                <img src="./images/logoNoBG.png" alt="logo">
            </div>
            <div class="bar">
                <i class="fa-solid fa-bars"></i>
                <i class="fa-solid fa-xmark" id="hdcross"></i>
            </div>
            <div class="nav">
                <ul>
                    <a href="index.php">
                        <li style="color: rgb(253, 57, 8);">Home</li>
                    </a>
                    <a href="about.php">
                        <li>About</li>
                    </a>
                    <a href="foodrecipe.php">
                        <li>Food Recipe</li>
                    </a>
                    <a href="social.php">
                        <li>Social</li>
                    </a>
                </ul>
            </div>
            <div class="account">
                <button class="loginBtn" id="form-open">Login</button>
            </div>
        </div>
    </header>
    <div class="home">

        <div class="main-slide">
            <div>
                <h1>Enjoy <span>Delicious Food</span> In Your Healthy Life.</h1>
                <p>Discover a world of flavors with Flavorize. From traditional recipes to the most innovative creations, find inspiration for every occasion. 
                    Customize your meals to suit your tastes and dietary needs. With Flavorize, healthy cooking is easy and fun.</p>

                <button class="red_btn">Get Started <i class="fa-solid fa-arrow-right-long"></i></button>
            </div>
            <div>
                <img src="./images/food.png" alt="Food Logo">
            </div>
        </div>

        <div class="food-items">
            <div class="item">
                <div>
                    <img src="./images/item1.png" alt="Item 1">
                </div>
                <h3>Power Bowl</h3>
                <p>Fuel your day with this energizing power bowl! 
                    Packed with protein-rich chickpeas, fiber-filled quinoa, and healthy fats from avocado, this dish will keep you satisfied and focused.</p>
                <button class="white_btn">See Menu</button>
            </div>
            <div class="item">
                <div>
                    <img src="./images/item2.png" alt="Item 1">
                </div>
                <h3>Tomato and Mozzarella Salad</h3>
                <p>A simple yet elegant salad that celebrates the flavors of summer. 
                    Juicy tomatoes, creamy mozzarella, and fresh basil are tossed with a light vinaigrette for a refreshing and delicious dish.</p>
                <button class="red_btn">See Menu</button>
            </div>
            <div class="item">
                <div>
                    <img src="./images/item3.png" alt="Item 1">
                </div>
                <h3> Rainbow Salad</h3>
                <p> A vibrant and refreshing salad that's perfect for any occasion. 
                    This colorful salad features roasted potatoes, tomatoes, cucumbers, mushrooms, mung beans, butter beans, avocado, and a variety of fresh greens.</p>
                <button class="white_btn">See Menu</button>
            </div>
        </div>

        <div class="main-slide2">
            <div class="foodimg">
                <img src="./images/plate1.png" alt="">
            </div>
            <div class="question">
                <div>
                    <h2>Why People Choose Us?</h2>
                </div>
                <div class="q-ans">
                    <div>
                        <img src="./images/thumbsup.jpg" alt="">
                    </div>
                    <div>
                        <h4>Personalized Recipe Recommendations</h4>
                        <p>Find recipes tailored to your dietary needs and available ingredients. 
                            Flavorize takes the guesswork out of meal planning, making it easy to choose dishes that are healthy, delicious, and meet your unique preferences.</p>
                    </div>
                </div>
                <div class="q-ans">
                    <div>
                        <img src="./images/community.jpg" alt="">
                    </div>
                    <div>
                        <h4>Community of Food Lovers</h4>
                        <p>Join a community where food enthusiasts connect, share recipes, and inspire each other. 
                            Our social features let you share your cooking experiences, get tips, and find motivation from like-minded individuals.</p>
                    </div>
                </div>
                <div class="q-ans">
                    <div>
                        <img src="./images/health.png" alt="">
                    </div>
                    <div>
                        <h4>Health-Focused and Nutritional Insights</h4>
                        <p>Discover the nutritional benefits of the ingredients in your meals. 
                            Flavorize promotes wellness by helping you understand how each recipe contributes to a balanced and healthy lifestyle.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-slide3">
            <div class="fav-head">
                <h3>Our Popular Food Items</h3>
                <p>Explore some of our most popular and mouth-watering recipes, specially curated for flavor and nutrition. 
                    From hearty meals to refreshing salads, each dish is crafted to satisfy your cravings while keeping you healthy.</p>
            </div>
            <div class="fav-food">
                <div class="item">
                    <div>
                        <img src="./images/plate1.png" alt="">
                    </div>
                    <h3>Grilled Chicken Quinoa Salad</h3>
                    <p>A wholesome blend of grilled chicken, fresh arugula, roasted pumpkin, and quinoa, tossed with a light vinaigrette for a nutritious boost.</p>
                    <p class="fav-price"></p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate2.png" alt="">
                    </div>
                    <h3>Lemon Garlic Salmon</h3>
                    <p>Fresh salmon fillet grilled to perfection with a zesty lemon garlic seasoning, served with sautéed greens and cherry tomatoes.</p>
                    <p class="fav-price"></p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate3.png" alt="">
                    </div>
                    <h3>Steak with Baby Potatoes</h3>
                    <p>Juicy steak seasoned with rosemary and garlic, paired with crispy roasted baby potatoes for a satisfying meal.</p>
                    <p class="fav-price"></p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate4.png" alt="">
                    </div>
                    <h3>Chicken & Veggie Platter</h3>
                    <p>Tender, seasoned chicken served with a colorful assortment of fresh, roasted vegetables, topped with a light herb garnish for a burst of flavor in every bite.</p>
                    <p class="fav-price"></p>
                </div>
            </div>
            <div class="dsgn"></div>
        </div>

        <div class="main-slide4">
            <div class="chef-feed">
                <h2>User <span style="color: red;">Feedback</span></h2>
                <p>"The Flavorize platform makes recipe exploration and food discovery so much easier and more enjoyable. 
                    It’s a community-driven experience where people come together to share their love for delicious, healthy food!"</p>
                <div class="chef-detail">
                    <div>
                        <img src="./images/logo.jpg" alt="">
                    </div>
                    <div>
                        <h6>Flavorize Group</h6>
                        <p>Web Developer</p>
                    </div>
                </div>
                <div class="chef-vic">
                    <div>
                        <i class="fa-solid fa-hand-peace"></i>
                        <h4>168</h4>
                        <p>Positive Reviews</p>
                    </div>
                    <div>
                        <i class="fa-solid fa-trophy"></i>
                        <h4>170</h4>
                        <p>Satisfied Users</p>
                    </div>
                </div>
            </div>
            <div class="chef">
                <img src="./images/logo2.png" alt="">
            </div>
        </div>

        <div class="form-overlay"></div>
        <div class="form-container" id="form-container">
            <i class="fa-solid fa-xmark form_close" id="form-close"></i>

            <div class="form login-form active">
                <form action="indexDB.php" method="post">
                    <h2>Login</h2>

                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <i class="fa-solid fa-envelope email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" id="login-password" placeholder="Enter your password" required>
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
    </div>

    <div class="footer">
        <div class="footer-1">
            <div class="logo">
                <img src="./images/logoNoBG.png" alt="">
            </div>
            <div>
                <address>
                    <p>Email: rapdeleon0404@gmail.com</p>
                    <p>Group: Flavorize</p>
                    <p>De Leon, Rafael D.</p>
                    <p>Maribbay, Rob Nesta Jaihra C.</p>
                    <p>Molina, John Angelo D.</p>
                </address>
            </div>
        </div>
        <div class="footer-2">
            <img src="./images/logo1.png" alt="">
            <h2>Created by <em>Flavorize Group</em></h2>
        </div>
    </div>
    <script>

        // Get the elements
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

        const bar = document.querySelector('.fa-bars');
        const cross = document.querySelector('#hdcross');
        const headerbar = document.querySelector('.headerbar');

        bar.addEventListener('click', function () {
            setTimeout(() => {
                cross.style.display = 'block';
            }, 200);
            headerbar.style.right = '0%';
        });

        cross.addEventListener('click', function () {
            cross.style.display = 'none';
            headerbar.style.right = '-100%';
        });
    </script>
</body>

</html>