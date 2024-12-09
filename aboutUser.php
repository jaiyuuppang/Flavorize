<?php
session_start();
include "database.php";

if (!isset($_SESSION['id'])) {
    // Redirect to login if session ID is not set
    header("Location: about.php");
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
                    <li><a href="indexUser.php">Home</a></li>
                    <li><a href="aboutUser.php" style="color: rgb(253, 57, 8);">About</a></li>
                    <li><a href="foodrecipeUser.php">Food Recipe</a></li>
                    <li><a href="social.php">Social</a></li>
                </ul>
            </nav>
            <div class="account">
                <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image" alt="Profile Picture">
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
            // Mobile Navigation Toggle
            const bar = document.querySelector('.bar');
            const headerbar = document.querySelector('.headerbar');
            const closeBtn = document.querySelector('.fa-xmark');

            bar.addEventListener('click', () => {
                headerbar.style.display = 'flex';
                headerbar.classList.add('active');
            });

            closeBtn.addEventListener('click', () => {
                headerbar.style.display = 'none';
                headerbar.classList.remove('active');
            });

            // Existing scripts...

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
        </script>
    </div>
</body>

</html>