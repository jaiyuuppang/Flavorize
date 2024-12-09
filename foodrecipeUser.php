<?php
session_start();

// Initialize ingredients array if not already set
if (!isset($_SESSION['ingredients'])) {
    $_SESSION['ingredients'] = [];
}

// Spoonacular API Key
$apiKey = "d1a63598a69d4fcc8b9cdb50ee14532f";

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

// Add low-carb filter option
$lowCarb = isset($_GET['low_carb']) ? $_GET['low_carb'] : 'false';
// Add keto diet option
$keto = isset($_GET['keto']) ? $_GET['keto'] : 'false';
// Add Mediterranean cuisine option
$mediterranean = isset($_GET['mediterranean']) ? $_GET['mediterranean'] : 'false';

// Check if search flag is set and there are ingredients
if (isset($_GET['search']) && !empty($_SESSION['ingredients'])) {
    $ingredients = implode(",", $_SESSION['ingredients']);

    // Step 1: Call complexSearch API with Mediterranean cuisine and health filters
    $complexSearchUrl = "https://api.spoonacular.com/recipes/complexSearch?" .
        "includeIngredients=" . urlencode($ingredients) .
        ($mediterranean === 'true' ? "&cuisine=Mediterranean" : "") . // Conditional Mediterranean cuisine
        ($lowCarb === 'true' ? "&diet=low-carb" : "") . // Conditional low-carb filter
        ($keto === 'true' ? "&diet=keto" : "") . // Conditional keto filter
        "&addRecipeInformation=true" .
        "&addRecipeNutrition=true" . // Add nutrition information
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

include "database.php";

if (!isset($_SESSION['id'])) {
    // Redirect to login if session ID is not set
    header("Location: foodrecipe.php");
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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="foodrecipe.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Food Recipes</title>
    <style>
        /* Styling for autocomplete and ingredient removal */
        .autocomplete-suggestions {
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            width: calc(100% - 20px);
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
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

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination-btn, .pagination-link {
            padding: 10px 15px;
            margin: 0 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination-btn.disabled, .pagination-link.active {
            background-color: #45a049;
            cursor: default;
        }

        .pagination-btn:hover:not(.disabled), .pagination-link:hover {
            background-color: #45a049;
        }

        .pagination-link {
            display: inline-block;
        }

        .pagination-link.active {
            font-weight: bold;
            text-decoration: none;
        }

        .pagination-ellipsis {
            padding: 0 10px;
            color: #4CAF50;
            font-weight: bold;
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
                    <a href="indexUser.php">
                        <li>Home</li>
                    </a>
                    <a href="aboutUser.php">
                        <li>About</li>
                    </a>
                    <a href="foodrecipeUser.php">
                        <li style="color: rgb(253, 57, 8);">Food Recipe</li>
                    </a>
                    <a href="social.php">
                        <li>Social</li>
                    </a>
                </ul>
            </div>
            <div class="account">
                <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image"
                    alt="Profile Picture">
            </div>
        </div>
    </header>
    <!-- SEARCH INGREDIENTS -->
    <div class="home">
        <div class="ingredient-input">
            <h2>What ingredients do you have?</h2>
            <form method="get" action="">
                <input type="text" id="ingredientSearch" name="ingredient" placeholder="Enter an ingredient...">
                <div id="suggestions" class="autocomplete-suggestions"></div>
                <button type="submit" name="search" value="1">Search Recipes</button>
                <button type="submit" name="reset">Reset</button>

                <!-- Diet and Cuisine Filters -->
                <div class="filters">
                    <label>
                        <input type="checkbox" name="low_carb" value="true" <?php echo $lowCarb === 'true' ? 'checked' : ''; ?>>
                        Low Carb
                    </label>
                    <label>
                        <input type="checkbox" name="keto" value="true" <?php echo $keto === 'true' ? 'checked' : ''; ?>>
                        Keto
                    </label>
                    <label>
                        <input type="checkbox" name="mediterranean" value="true" <?php echo $mediterranean === 'true' ? 'checked' : ''; ?>>
                        Mediterranean
                    </label>
                </div>
            </form>

            <!-- Display added ingredients -->
            <div class="added-ingredients">
                <h3>Added Ingredients:</h3>
                <?php foreach ($_SESSION['ingredients'] as $ingredient): ?>
                    <div class="ingredient-item">
                        <?php echo htmlspecialchars($ingredient); ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="remove_ingredient" value="<?php echo htmlspecialchars($ingredient); ?>">
                            <button type="submit" class="remove-btn">&times;</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="recipe-container">
            <div class="food-items">
                <?php if (!empty($recipes)): ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="item">
                            <div class="item-img">
                                <img src="<?php echo $recipe['image']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                            </div>
                            <div class="item-content">
                                <h2 class="item-title"><?php echo htmlspecialchars($recipe['title']); ?></h2>
                                <p class="item-body">
                                    Ready in <?php echo $recipe['readyInMinutes']; ?> minutes<br>
                                    Servings: <?php echo $recipe['servings']; ?>
                                </p>
                                <div class="item-footer">
                                    <button onclick="viewRecipeDetails(<?php echo $recipe['id']; ?>)" class="view-recipe-btn">View Recipe</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recipes found. Try adding some ingredients and searching!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recipe Modal -->
        <div id="recipeModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <div id="recipeDetails"></div>
            </div>
        </div>
    </div>

    <script>
        const apiKey = "<?php echo $apiKey; ?>";
        const ingredientSearch = document.getElementById('ingredientSearch');
        const suggestionsDiv = document.getElementById('suggestions');

        // Ingredient Search Autocomplete
        ingredientSearch.addEventListener('input', () => {
            const query = ingredientSearch.value;

            if (query.length > 1) {
                fetch(`https://api.spoonacular.com/food/ingredients/autocomplete?query=${query}&number=10&apiKey=${apiKey}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsDiv.innerHTML = '';
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-suggestion';
                            div.textContent = item.name;
                            div.onclick = () => {
                                ingredientSearch.value = item.name;
                                suggestionsDiv.innerHTML = '';
                            };
                            suggestionsDiv.appendChild(div);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                suggestionsDiv.innerHTML = '';
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target !== ingredientSearch) {
                suggestionsDiv.innerHTML = '';
            }
        });

        // Recipe Modal
        const modal = document.getElementById("recipeModal");
        const closeBtn = document.querySelector(".close-btn");

        function closeModal() {
            modal.style.display = "none";
        }

        closeBtn.onclick = closeModal;

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        function viewRecipeDetails(recipeId) {
            modal.style.display = "block";
            document.getElementById('recipeDetails').innerHTML = 'Loading...';

            fetch(`https://api.spoonacular.com/recipes/${recipeId}/information?apiKey=${apiKey}`)
                .then(response => response.json())
                .then(recipe => {
                    let content = `
                        <h2>${recipe.title}</h2>
                        <img src="${recipe.image}" alt="${recipe.title}" style="max-width: 100%; height: auto;">
                        <div class="recipe-info">
                            <p><strong>Ready in:</strong> ${recipe.readyInMinutes} minutes</p>
                            <p><strong>Servings:</strong> ${recipe.servings}</p>
                            <p><strong>Health Score:</strong> ${recipe.healthScore}</p>
                        </div>
                        <h3>Ingredients:</h3>
                        <ul>
                            ${recipe.extendedIngredients.map(ingredient => 
                                `<li>${ingredient.original}</li>`
                            ).join('')}
                        </ul>
                        <h3>Instructions:</h3>
                        ${recipe.instructions ? 
                            `<ol>${recipe.analyzedInstructions[0].steps.map(step => 
                                `<li>${step.step}</li>`
                            ).join('')}</ol>` : 
                            '<p>No instructions available.</p>'}
                        ${recipe.sourceUrl ? 
                            `<p><a href="${recipe.sourceUrl}" target="_blank">View Original Recipe</a></p>` : 
                            ''}
                    `;
                    document.getElementById('recipeDetails').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('recipeDetails').innerHTML = 'Error loading recipe details.';
                });
        }
    </script>
</body>

</html>