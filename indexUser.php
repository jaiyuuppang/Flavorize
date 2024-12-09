<?php
session_start();
include "database.php";

if (!isset($_SESSION['id'])) {
    // Redirect to login if session ID is not set
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];

// Prepare the SQL statement to prevent SQL injection
$sql = $conn->prepare("SELECT profile FROM users WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profile = $row['profile'];
} else {
    echo "User not found.";
    exit();
}
$sql->close();
?>

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
                    <a href="indexUser.php">
                        <li style="color: rgb(253, 57, 8);">Home</li>
                    </a>
                    <a href="aboutUser.php">
                        <li>About</li>
                    </a>
                    <a href="foodrecipeUser.php">
                        <li>Food Recipe</li>
                    </a>
                    <a href="social.php">
                        <li>Social</li>
                    </a>
                </ul>
            </div>
            <div class="account">
                <div class="dropdown">
                    <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image"
                        id="dropdown-toggle">
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="index.php" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>

        </div>
    </header>
    <div class="home">

        <div class="main-slide">
            <div>
                <h1>Enjoy <span>Delicious Food</span> In Your Healthy Life.</h1>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Possimus cumque molestiae ex excepturi
                    optio laborum repellendus aspernatur doloribus id molestias. Magni possimus, tempore ducimus velit
                    fugiat natus sit ipsa obcaecati?</p>

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
                <h3>Food Name</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos, molestiae?</p>
                <button class="white_btn">See Menu</button>
            </div>
            <div class="item">
                <div>
                    <img src="./images/item2.png" alt="Item 1">
                </div>
                <h3>Food Name</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos, molestiae?</p>
                <button class="red_btn">See Menu</button>
            </div>
            <div class="item">
                <div>
                    <img src="./images/item3.png" alt="Item 1">
                </div>
                <h3>Food Name</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos, molestiae?</p>
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
                        <img src="./images/plate2.png" alt="">
                    </div>
                    <div>
                        <h4>Choose your favourite</h4>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis unde molestiae et!</p>
                    </div>
                </div>
                <div class="q-ans">
                    <div>
                        <img src="./images/plate3.png" alt="">
                    </div>
                    <div>
                        <h4>Choose your favourite</h4>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis unde molestiae et!</p>
                    </div>
                </div>
                <div class="q-ans">
                    <div>
                        <img src="./images/plate4.png" alt="">
                    </div>
                    <div>
                        <h4>Choose your favourite</h4>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis unde molestiae et!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-slide3">
            <div class="fav-head">
                <h3>Our Popular Food Items</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate, odio! Quia necessitatibus
                    aspernatur dolor quae incidunt. Consequatur necessitatibus magni est quasi, aspernatur sint quo
                    aperiam quod sed, sequi suscipit ad?</p>
            </div>
            <div class="fav-food">
                <div class="item">
                    <div>
                        <img src="./images/plate1.png" alt="">
                    </div>
                    <h3>Food Name</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem et repudiandae repellat.</p>
                    <p class="fav-price">$2.90</p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate2.png" alt="">
                    </div>
                    <h3>Food Name</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem et repudiandae repellat.</p>
                    <p class="fav-price">$2.90</p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate3.png" alt="">
                    </div>
                    <h3>Food Name</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem et repudiandae repellat.</p>
                    <p class="fav-price">$2.90</p>
                </div>
                <div class="item">
                    <div>
                        <img src="./images/plate4.png" alt="">
                    </div>
                    <h3>Food Name</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem et repudiandae repellat.</p>
                    <p class="fav-price">$2.90</p>
                </div>
            </div>
            <div class="dsgn"></div>
        </div>

        <div class="main-slide4">
            <div class="chef-feed">
                <h2>Customer <span style="color: red;">Feedback</span></h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio, corrupti! Velit corrupti a
                    molestiae non dolor provident cum dolorem magni architecto soluta ratione, impedit quidem. Pariatur
                    sequi dicta, neque debitis animi reiciendis, sapiente nisi odit eveniet ducimus quo modi perferendis
                    numquam reprehenderit suscipit, non incidunt omnis itaque cum quidem. Tempore.</p>
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
                        <h4>68</h4>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>
                    <div>
                        <i class="fa-solid fa-trophy"></i>
                        <h4>956</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing.</p>
                    </div>
                </div>
            </div>
            <div class="chef">
                <img src="./images/logo2.png" alt="">
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

        document.addEventListener("DOMContentLoaded", () => {
            const dropdownToggle = document.getElementById("dropdown-toggle");
            const dropdownMenu = document.getElementById("dropdown-menu");

            // Toggle dropdown menu visibility on click
            dropdownToggle.addEventListener("click", () => {
                dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
            });

            // Hide dropdown menu if clicked outside
            document.addEventListener("click", (event) => {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = "none";
                }
            });
        });

    </script>
</body>

</html>