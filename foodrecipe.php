<?php
session_start();

// Initialize ingredients array if not already set
if (!isset($_SESSION['ingredients'])) {
    $_SESSION['ingredients'] = [];
}

// Spoonacular API Key
$apiKey = "93fc7f6eeb1e46839c3a9b58af81eaa6";

// Initialize variables
$totalPages = 0; // Initialize totalPages
$recipes = []; // Initialize recipes

// Logic to handle form actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset'])) {
        $_SESSION['ingredients'] = [];
    } elseif (isset($_POST['ingredient'])) {
        $ingredient = htmlspecialchars(trim($_POST['ingredient']));
        if (!empty($ingredient)) {
            $_SESSION['ingredients'][] = $ingredient;
        }
    } elseif (isset($_POST['remove_ingredient'])) {
        $removeIngredient = $_POST['remove_ingredient'];
        if (($key = array_search($removeIngredient, $_SESSION['ingredients'])) !== false) {
            unset($_SESSION['ingredients'][$key]);
            $_SESSION['ingredients'] = array_values($_SESSION['ingredients']);
        }
    }
}

// Handle recipe search
$recipesPerPage = 5; // Number of recipes to display per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recipesPerPage;

// Check if search flag is set and there are ingredients
if (isset($_GET['search']) && !empty($_SESSION['ingredients'])) {
    $ingredients = implode(",", $_SESSION['ingredients']);

    // Step 1: Call complexSearch API with Mediterranean cuisine and health filters
    $complexSearchUrl = "https://api.spoonacular.com/recipes/complexSearch?" .
        "includeIngredients=" . urlencode($ingredients) .
        "&cuisine=Mediterranean" .
        "&maxCarbs=50" .
        "&maxSugar=10" .
        "&maxCalories=500" .
        "&addRecipeInformation=true" .
        "&fillIngredients=true" .
        "&number=" . $recipesPerPage .
        "&offset=" . $offset . // Add offset for pagination
        "&apiKey=" . $apiKey;

    $complexSearchResponse = file_get_contents($complexSearchUrl);
    $complexSearchData = json_decode($complexSearchResponse, true);

    if (isset($complexSearchData['status']) && $complexSearchData['status'] === 'failure') {
        $errorMessage = $complexSearchData['message'];
        echo "<script>alert('API Error: $errorMessage');</script>";
        return; // Exit the script if quota is exceeded
    }

    // Step 2: Process recipes
    $recipes = $complexSearchData['results'];
    $totalRecipes = $complexSearchData['totalResults']; // Get total number of recipes
    $totalPages = ceil($totalRecipes / $recipesPerPage); // Calculate total pages
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="foodrecipe.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Food Recipes</title>
    <style>
        /* Styling for autocomplete and ingredient removal */
        .autocomplete-suggestions {
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            width: calc(100% - 20px);
            z-index: 10;
        }

        .autocomplete-suggestion {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-suggestion:hover {
            background-color: #f0f0f0;
        }

        .ingredient-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px;
        }

        .remove-btn {
            background: none;
            border: none;
            color: red;
            font-weight: bold;
            cursor: pointer;
            margin-left: 5px;
        }

        .remove-btn:hover {
            color: darkred;
        }

        /* Styling for pagination controls */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination button {
 padding: 10px;
            margin: 0 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .pagination button:hover {
            background-color: #45a049;
        }
    </style>
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
                        <li>Home</li>
                    </a>
                    <a href="about.php">
                        <li>About</li>
                    </a>
                    <a href="foodrecipe.php">
                        <li style="color: rgb(253, 57, 8);">Food Recipe</li>
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
    <!-- SEARCH INGREDIENTS -->
    <div class="home">
        <div class="ingredient-input">
            <h2>What ingredients do you have?</h2>
            <form method="post" action="">
                <input type="text" id="ingredientSearch" name="ingredient" placeholder="Enter an ingredient..."
                    required>
                <div class="autocomplete-suggestions" id="suggestions"></div>
                <input type="submit" class="white_btn" value="Add Ingredient">
            </form>
        </div>

        <!-- LIST OF INGREDIENTS -->
        <div class="ingredients-list">
            <h3>Your Ingredients:</h3>
            <ul>
                <?php foreach ($_SESSION['ingredients'] as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient) ?>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="remove_ingredient" value="<?= htmlspecialchars($ingredient) ?>">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>You currently have <?php echo count($_SESSION['ingredients']); ?> ingredients.</p>
        </div>

        <div class="reset-container">
            <form method="post" action="">
                <input type="submit" name="reset" class="red_btn" value="Reset Ingredients">
            </form>
        </div>

       
        
        <div class="search-container">
        <form method="get" action="">
            <input type="hidden" name="search" value="1">
            <input type="submit" class="blue_btn" value="Search for Recipes">
        </form>
    </div>

        <div class="recipe-container">
            <div class="food-items">
                <?php if (!empty($recipes)): ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="item">
                            <div><img src="<?php echo htmlspecialchars($recipe['image']); ?>"
                                    alt="<?php echo htmlspecialchars($recipe['title']); ?>"></div>
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>

                            <!-- Used Ingredients List -->
                            <p><strong>Used Ingredients:</strong></p>
                            <ul>
                                <?php if (!empty($recipe['usedIngredients'])): ?>
                                    <?php foreach ($recipe['usedIngredients'] as $usedIngredient): ?>
                                        <li class="used"><?php echo htmlspecialchars($usedIngredient['originalName']); ?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>No used ingredients found</li>
                                <?php endif; ?>
                            </ul>

                            <!-- Missing Ingredients List -->
                            <p><strong>Missing Ingredients:</strong></p>
                            <ul>
                                <?php if (!empty($recipe['missedIngredients'])): ?>
                                    <?php foreach ($recipe['missedIngredients'] as $missedIngredient): ?>
                                        <li class="missing"><?php echo htmlspecialchars($missedIngredient['originalName']); ?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>No missing ingredients found</li>
                                <?php endif; ?>
                            </ul>

                            <button onclick="viewRecipeDetails(<?php echo htmlspecialchars($recipe['id']); ?>)"
                                class="white_btn">View Recipe</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Please add ingredients and click "Search for Recipes" to get recipe suggestions.</p>
                <?php endif; ?>
            </div>
        </div>
 <!-- Pagination Controls -->
 <?php if ($totalPages > 0): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&ingredient=<?php echo urlencode(implode(',', $_SESSION['ingredients'])); ?>" class="<?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
        <!-- Recipe Modal -->
        <div id="recipeModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <div class="modal-header">
                    <h2 id="recipeTitle">Recipe Title</h2>
                </div>
                <div class="modal-body">
                    <img id="recipeImage" src="" alt="Recipe Image" />
                    <div class="recipe-info">
                        <h3>Ingredients:</h3>
                        <ul id="recipeIngredients"></ul>
                        <h3>Instructions:</h3>
                        <p id="recipeInstructions"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-overlay"></div>
        <div class="form-container" id="form-container">
            <i class="fa-solid fa-xmark form_close" id="form-close"></i>

            <div class="form login-form active">
                <form action="foodrecipeDB.php" method="post">
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

        <script>
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

            const ingredientSearch = document.getElementById('ingredientSearch');
            const suggestions = document.getElementById('suggestions');
            const apiKey = "<?php echo $apiKey; ?>"; // Spoonacular API key

            // Show suggestions based on input
            ingredientSearch.addEventListener('input', () => {
                const query = ingredientSearch.value;

                if (query.length > 1) { // Start searching after a few letters
                    fetch(`https://api.spoonacular.com/food/ingredients/autocomplete?query=${query}&number=10&apiKey=${apiKey}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestions.innerHTML = ''; // Clear previous suggestions
                            data.forEach(item => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('autocomplete-suggestion');
                                suggestionItem.textContent = item.name;

                                suggestionItem.addEventListener(' click', () => {
                                    ingredientSearch.value = item.name;
                                    suggestions.innerHTML = ''; // Clear suggestions
                                });

                                suggestions.appendChild(suggestionItem);
                            });
                        })
                        .catch(error => console.error('Error fetching suggestions:', error));
                } else {
                    suggestions.innerHTML = ''; // Clear suggestions if input is too short
                }
            });

            // Hide suggestions when clicking outside the input
            document.addEventListener('click', (event) => {
                if (!ingredientSearch.contains(event.target) && !suggestions.contains(event.target)) {
                    suggestions.innerHTML = '';
                }
            });

            // View Recipe pop-up
            function viewRecipeDetails(recipeId) {
                const apiKey = "<?php echo $apiKey; ?>";

                fetch(`https://api.spoonacular.com/recipes/${recipeId}/information?apiKey=${apiKey}`)
                    .then(response => response.json())
                    .then(recipe => {
                        // Update modal content
                        document.getElementById("recipeTitle").textContent = recipe.title;
                        document.getElementById("recipeImage").src = recipe.image;

                        // Clear old ingredients
                        const ingredientsList = document.getElementById("recipeIngredients");
                        ingredientsList.innerHTML = '';

                        // Add ingredients
                        recipe.extendedIngredients.forEach(ingredient => {
                            const li = document.createElement('li');
                            li.textContent = ingredient.original;
                            ingredientsList.appendChild(li);
                        });

                        // Check if instructions are structured
                        const instructions = recipe.instructions || "No instructions provided.";
                        const instructionsList = document.createElement('ol');

                        // Clean up instructions if they contain dual numbering
                        const cleanedInstructions = instructions.replace(/\d+\.\d+\./g, match => {
                            return match.split('.')[0] + '.';
                        });

                        // Split cleaned instructions into steps and add them to an ordered list
                        const instructionSteps = cleanedInstructions.split('.');
                        instructionSteps.forEach(step => {
                            if (step.trim()) {
                                const li = document.createElement('li');
                                li.textContent = step.trim();
                                instructionsList.appendChild(li);
                            }
                        });

                        document.getElementById("recipeInstructions").innerHTML = '';
                        document.getElementById("recipeInstructions").appendChild(instructionsList);

                        // Show modal
                        const modal = document.getElementById("recipeModal");
                        modal.style.display = "block";
                    })
                    .catch(error => console.error('Error fetching recipe details:', error));
            }

            // Close Modal Function
            function closeModal() {
                const modal = document.getElementById("recipeModal");
                modal.style.display = "none";
            }

            // Event Listener for Close Button
            document.querySelector(".close-btn").addEventListener("click", closeModal);

            // Close Modal on Outside Click
            window.onclick = function (event) {
                const modal = document.getElementById("recipeModal");
                if (event.target === modal) {
                    closeModal();
                }
            };
        </script>
</body>

</html>