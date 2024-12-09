<?php
session_start();

// Initialize ingredients array if not already set
if (!isset($_SESSION['ingredients'])) {
    $_SESSION['ingredients'] = [];
    $_SESSION['ingredients_data'] = []; // Store both GI and GL information
}

// RapidAPI Configuration
$rapidApiKey = "17177631b4msh2313656981af13cp17a208jsne82f2d8c17ee";
$rapidApiHost = "spoonacular-recipe-food-nutrition-v1.p.rapidapi.com";

// Function to make POST request to Spoonacular API via RapidAPI
function getGlycemicData($ingredients) {
    global $rapidApiKey, $rapidApiHost;
    
    $url = "https://" . $rapidApiHost . "/food/ingredients/glycemicLoad";
    
    $postData = json_encode([
        "ingredients" => $ingredients
    ]);
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'x-rapidapi-host: ' . $rapidApiHost,
                'x-rapidapi-key: ' . $rapidApiKey,
                'Content-Length: ' . strlen($postData)
            ],
            'content' => $postData
        ]
    ];
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return null;
    }
    
    return json_decode($response, true);
}

// Logic to handle form actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset'])) {
        $_SESSION['ingredients'] = [];
        $_SESSION['ingredients_data'] = [];
    } elseif (isset($_POST['ingredient'])) {
        $ingredient = htmlspecialchars(trim($_POST['ingredient']));
        if (!empty($ingredient)) {
            // Check if ingredient already exists (case-insensitive)
            $exists = false;
            foreach ($_SESSION['ingredients'] as $existingIngredient) {
                if (strcasecmp($existingIngredient, $ingredient) === 0) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                // Format ingredient with "1" as default quantity
                $formattedIngredient = "1 " . $ingredient;
                $response = getGlycemicData([$formattedIngredient]);
                
                if ($response && isset($response['ingredients']) && !empty($response['ingredients'])) {
                    $ingredientData = $response['ingredients'][0];
                    $_SESSION['ingredients'][] = $ingredient;
                    $_SESSION['ingredients_data'][] = [
                        'name' => $ingredient,
                        'original' => $ingredientData['original'],
                        'gi' => $ingredientData['glycemicIndex'],
                        'gl' => $ingredientData['glycemicLoad']
                    ];
                } else {
                    // If API call fails, still add ingredient but mark GI and GL as N/A
                    $_SESSION['ingredients'][] = $ingredient;
                    $_SESSION['ingredients_data'][] = [
                        'name' => $ingredient,
                        'original' => "1 " . $ingredient,
                        'gi' => 'N/A',
                        'gl' => 'N/A'
                    ];
                }
            }
        }
    } elseif (isset($_POST['remove_ingredient'])) {
        $removeIngredient = $_POST['remove_ingredient'];
        if (($key = array_search($removeIngredient, $_SESSION['ingredients'])) !== false) {
            unset($_SESSION['ingredients'][$key]);
            unset($_SESSION['ingredients_data'][$key]);
            $_SESSION['ingredients'] = array_values($_SESSION['ingredients']);
            $_SESSION['ingredients_data'] = array_values($_SESSION['ingredients_data']);
        }
    }
}

// Initialize variables
$totalPages = 0; // Initialize totalPages
$recipes = []; // Initialize recipes

// Add low-carb filter option
$lowCarb = isset($_GET['low_carb']) ? $_GET['low_carb'] : 'false';
// Add keto diet option
$keto = isset($_GET['keto']) ? $_GET['keto'] : 'false';
// Add Mediterranean cuisine option
$mediterranean = isset($_GET['mediterranean']) ? $_GET['mediterranean'] : 'false';

// Handle recipe search
$recipesPerPage = 5; // Number of recipes to display per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recipesPerPage;

// Check if search flag is set and there are ingredients
if (isset($_GET['search']) && !empty($_SESSION['ingredients'])) {
    $ingredients = implode(",", $_SESSION['ingredients']);

    // Step 1: Call complexSearch API with Mediterranean cuisine and health filters
    $complexSearchUrl = "https://" . $rapidApiHost . "/recipes/complexSearch?" .
        "includeIngredients=" . urlencode($ingredients) .
        ($mediterranean === 'true' ? "&cuisine=Mediterranean" : "") . // Conditional Mediterranean cuisine
        ($lowCarb === 'true' ? "&diet=low-carb" : "") . // Conditional low-carb filter
        ($keto === 'true' ? "&diet=keto" : "") . // Conditional keto filter
        "&addRecipeInformation=true" .
        "&addRecipeNutrition=true" . // Add nutrition information
        "&fillIngredients=true" .
        "&number=" . $recipesPerPage .
        "&offset=" . $offset . // Add offset for pagination
        "&x-rapidapi-host=" . $rapidApiHost .
        "&x-rapidapi-key=" . $rapidApiKey;

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
        .search-container {
            position: relative;
            width: 100%;
        }

        .autocomplete-suggestions {
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            width: 100%;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 2px;
        }

        #ingredientSearch {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .autocomplete-suggestion {
            padding: 8px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }

        .autocomplete-suggestion:hover {
            background-color: #f8f8f8;
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
            color: #ff4d4d;
            cursor: pointer;
            padding: 4px 8px;
            transition: all 0.2s ease;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background-color: #ff4d4d;
            color: white;
            transform: scale(1.1);
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
    background-color: #45a049; /* Darker green for active/disabled */
    cursor: default; /* Disable pointer for disabled buttons */
}

.pagination-btn:hover:not(.disabled), .pagination-link:hover {
    background-color: #45a049; /* Darker green on hover */
}

.pagination-link {
    display: inline-block; /* Ensure links are inline */
}

.pagination-link.active {
    font-weight: bold; /* Make active link bold */
    text-decoration: none; /* Remove underline from active link */
}

.pagination-ellipsis {
    padding: 0 10px; /* Space around ellipsis */
    color: #4CAF50; /* Color for ellipsis */
    font-weight: bold; /* Make it bold */
}
     
    </style>
    <style>
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .header {
                padding: 10px;
            }

            .headerbar {
                flex-direction: column;
                gap: 10px;
            }

            .search-container {
                width: 100%;
                padding: 10px;
            }

            .search-container form {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .search-container label {
                margin: 5px 0;
            }

            .ingredient-input {
                width: 100%;
                margin: 5px 0;
            }

            .food-items {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 10px;
            }

            .item {
                width: 100%;
                margin: 0;
            }

            .item img {
                width: 100%;
                height: auto;
            }

            .details {
                padding: 10px;
            }

            .details-sub {
                margin: 5px 0;
            }

            /* Modal Responsiveness */
            .modal-content {
                width: 95%;
                margin: 20px auto;
                padding: 15px;
            }

            #recipeImage {
                width: 100%;
                height: auto;
            }

            #recipeIngredients, #recipeInstructions {
                padding: 10px;
            }

            /* Pagination Responsiveness */
            .pagination {
                flex-wrap: wrap;
                gap: 5px;
                justify-content: center;
                padding: 10px;
            }

            .pagination-btn, .pagination-link {
                padding: 8px 12px;
                margin: 2px;
                font-size: 14px;
            }

            /* Form Responsiveness */
            .form-container {
                width: 95%;
                max-width: none;
                margin: 20px auto;
            }

            .form {
                padding: 15px;
            }

            .input-field {
                width: 100%;
            }

            /* Filter Checkboxes */
            .filter-options {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin: 10px 0;
            }

            .filter-option {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            /* Suggestions Dropdown */
            .suggestions {
                width: 100%;
                max-width: none;
            }
        }

        /* Small phones */
        @media screen and (max-width: 480px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .blue_btn, .white_btn {
                padding: 8px 15px;
                font-size: 14px;
            }

            .modal-content {
                padding: 10px;
            }

            .close-btn {
                font-size: 24px;
            }
        }
    </style>
    <style>
        /* Mobile Navigation Styles */
        @media screen and (max-width: 768px) {
            .header {
                position: relative;
                padding: 10px;
            }

            .nav {
                display: none;
            }

            .bar {
                display: block;
                position: absolute;
                top: 20px;
                right: 20px;
                z-index: 1000;
                cursor: pointer;
            }

            .bar i {
                font-size: 24px;
                color: rgb(253, 57, 8);
            }

            .headerbar {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(248, 232, 217, 0.95);
                z-index: 999;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
            }

            .headerbar .nav {
                display: block;
                width: 100%;
            }

            .headerbar .nav ul {
                flex-direction: column;
            }

            .headerbar .nav ul a {
                display: block;
                padding: 15px;
                border-bottom: 1px solid rgba(253, 57, 8, 0.1);
                width: 100%;
            }

            .headerbar .account {
                margin-top: 20px;
            }

            .headerbar .loginBtn {
                padding: 10px 30px;
            }

            .fa-xmark {
                display: none !important;
                visibility: hidden;
            }

            .headerbar.active .fa-xmark {
                display: none !important;
                visibility: hidden;
            }
        }
    </style>
    <style>
        .ingredient-form {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .ingredient-form input[type="text"] {
            flex: 1;
            min-width: 200px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .ingredient-form input[type="text"]:focus {
            border-color: #ff4d4d;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.1);
        }

        .ingredient-form button[type="submit"] {
            padding: 12px 25px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .ingredient-form button[type="submit"]:hover {
            background-color: #ff3333;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ingredient-form button[type="submit"]:active {
            transform: translateY(0);
            box-shadow: none;
        }

        @media (max-width: 480px) {
            .ingredient-form {
                flex-direction: column;
            }

            .ingredient-form input[type="text"] {
                width: 100%;
            }

            .ingredient-form button[type="submit"] {
                width: 100%;
            }
        }
    </style>
    <style>
        /* Chatbot styles */
        .chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .chat-button i {
            color: white;
            font-size: 24px;
        }
        
        .chat-iframe {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 400px;
            height: 600px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: none;
            z-index: 1000;
        }
    </style>
    <style>
        .error-message {
            color: #ff4d4d;
            font-size: 0.9em;
            margin-top: 5px;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <style>
        .close-btn {
            color: rgb(253, 57, 8);
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #c51f1f;
        }

        .form_close {
            color: rgb(253, 57, 8);
            font-size: 24px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .form_close:hover {
            color: #c51f1f;
        }
    </style>
    <style>
        .home {
            background-color: rgb(248, 232, 217);
            padding: 0 7vw;
        }
    </style>
    <style>
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .recipe-video {
            margin: 20px 0;
            width: 100%;
            max-width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        .recipe-video iframe {
            width: 100%;
            aspect-ratio: 16/9;
            border: none;
            border-radius: 8px;
        }

        .video-placeholder {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: #666;
            margin: 20px 0;
        }
    </style>
    <style>
        .ingredient-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background-color: #f8f8f8;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .ingredient-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .nutrition-data {
            display: flex;
            gap: 10px;
        }

        .gi-indicator, .gl-indicator {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: 500;
            min-width: 80px;
            text-align: center;
        }

        .low {
            background-color: #4CAF50;
            color: white;
        }

        .medium {
            background-color: #FFA726;
            color: white;
        }

        .high {
            background-color: #EF5350;
            color: white;
        }

        .na {
            background-color: #90A4AE;
            color: white;
        }

        .total-glycemic {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .ingredient-name {
            font-weight: 500;
            min-width: 120px;
        }
    </style>
    <style>
        .diet-preferences {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .diet-preferences label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px 16px;
            background-color: #f5f5f5;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .diet-preferences label:hover {
            background-color: #e0e0e0;
        }

        .diet-preferences input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .diet-preferences input[type="checkbox"]:checked + label {
            background-color: #4CAF50;
            color: white;
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
                    <a href="javascript:void(0);" onclick="checkLogin()">
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
        <div class="ingredient-form">
            <h2>What ingredients do you have?</h2>
            <form method="post" style="display: flex; gap: 10px; width: 100%;">
                <input type="text" id="ingredientSearch" name="ingredient" placeholder="Enter an ingredient..." required>
                <button type="submit" class="white_btn" style="background-color: #ff4d4d; color: white;">Add Ingredient</button>
            </form>
            <div class="error-message" id="ingredient-error">This ingredient is already in your list</div>
        </div>

        <!-- LIST OF INGREDIENTS -->
        <div class="ingredients-list">
            <h3>Your Ingredients:</h3>
            <ul>
                <?php 
                $totalGL = 0;
                $hasValidData = false;
                foreach ($_SESSION['ingredients_data'] as $index => $ingredient): 
                    if ($ingredient['gl'] !== 'N/A') {
                        $totalGL += $ingredient['gl'];
                        $hasValidData = true;
                    }
                ?>
                    <li class="ingredient-item">
                        <div class="ingredient-info">
                            <span class="ingredient-name"><?= htmlspecialchars($ingredient['name']) ?></span>
                            <div class="nutrition-data">
                                <?php
                                // Glycemic Index indicator
                                $giClass = 'na';
                                $giText = 'GI: N/A';
                                
                                if ($ingredient['gi'] !== 'N/A') {
                                    $gi = $ingredient['gi'];
                                    if ($gi <= 55) {
                                        $giClass = 'low';
                                        $giText = "GI: $gi (Low)";
                                    } elseif ($gi <= 69) {
                                        $giClass = 'medium';
                                        $giText = "GI: $gi (Med)";
                                    } else {
                                        $giClass = 'high';
                                        $giText = "GI: $gi (High)";
                                    }
                                }
                                
                                // Glycemic Load indicator
                                $glClass = 'na';
                                $glText = 'GL: N/A';
                                
                                if ($ingredient['gl'] !== 'N/A') {
                                    $gl = $ingredient['gl'];
                                    if ($gl <= 10) {
                                        $glClass = 'low';
                                        $glText = "GL: $gl (Low)";
                                    } elseif ($gl <= 19) {
                                        $glClass = 'medium';
                                        $glText = "GL: $gl (Med)";
                                    } else {
                                        $glClass = 'high';
                                        $glText = "GL: $gl (High)";
                                    }
                                }
                                ?>
                                <span class="gi-indicator <?= $giClass ?>"><?= $giText ?></span>
                                <span class="gl-indicator <?= $glClass ?>"><?= $glText ?></span>
                            </div>
                        </div>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="remove_ingredient" value="<?= htmlspecialchars($ingredient['name']) ?>" class="remove-btn">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (!empty($_SESSION['ingredients'])): ?>
                <div class="total-glycemic">
                    <div>
                        Total Glycemic Load: <?= $hasValidData ? round($totalGL, 1) : 'N/A' ?>
                        <?php if ($hasValidData): ?>
                            <?php
                            $totalClass = $totalGL <= 10 ? 'low' : ($totalGL <= 19 ? 'medium' : 'high');
                            $totalText = $totalGL <= 10 ? '(Low)' : ($totalGL <= 19 ? '(Medium)' : '(High)');
                            ?>
                            <span class="gl-indicator <?= $totalClass ?>"><?= $totalText ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <p>You currently have <?php echo count($_SESSION['ingredients']); ?> ingredients.</p>
        </div>

        <div class="reset-container">
            <form method="post" action="">
                <input type="submit" name="reset" class="red_btn" value="Reset Ingredients">
            </form>
        </div>

        <div class="diet-preferences">
            <label>
                <input type="checkbox" id="lowCarb" name="low_carb" <?php echo $lowCarb === 'true' ? 'checked' : ''; ?>>
                Low Carb
            </label>
            <label>
                <input type="checkbox" id="keto" name="keto" <?php echo $keto === 'true' ? 'checked' : ''; ?>>
                Keto
            </label>
            <label>
                <input type="checkbox" id="mediterranean" name="mediterranean" <?php echo $mediterranean === 'true' ? 'checked' : ''; ?>>
                Mediterranean
            </label>
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

                            <!-- Time and Servings Info -->
                            <div class="details">
                                <div class="details-sub">
                                    <p><strong>Prep Time:</strong> <?php echo isset($recipe['readyInMinutes']) ? $recipe['readyInMinutes'] . ' mins' : 'N/A'; ?></p>
                                    <p><strong>Servings:</strong> <?php echo isset($recipe['servings']) ? $recipe['servings'] : 'N/A'; ?></p>
                                </div>

                                <!-- Nutrition Facts -->
                                <div class="details-sub">
                                    <p><strong>Nutrition Facts (per serving):</strong></p>
                                    <?php if (isset($recipe['nutrition']['nutrients'])): ?>
                                        <ul>
                                            <li>Calories: <?php echo round($recipe['nutrition']['nutrients'][0]['amount']); ?> kcal</li>
                                            <li>Protein: <?php echo round($recipe['nutrition']['nutrients'][8]['amount']); ?>g</li>
                                            <li>Carbs: <?php echo round($recipe['nutrition']['nutrients'][3]['amount']); ?>g</li>
                                            <li>Fat: <?php echo round($recipe['nutrition']['nutrients'][1]['amount']); ?>g</li>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>

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
        <button onclick="location.href='?page=<?php echo max(1, $currentPage - 1); ?>&search=1&ingredient=<?php echo urlencode(implode(',', $_SESSION['ingredients'])); ?>&low_carb=<?php echo $lowCarb; ?>&keto=<?php echo $keto; ?>&mediterranean=<?php echo $mediterranean; ?>'" 
                class="pagination-btn <?php echo ($currentPage === 1) ? 'disabled' : ''; ?>">
            &laquo; Previous
        </button>
        
        <?php
        // Determine the range of pages to display
        $startPage = max(1, $currentPage - 1);
        $endPage = min($totalPages, $currentPage + 2);
        
        // Adjust the start and end page if there are more than 4 pages
        if ($totalPages > 4) {
            if ($currentPage < 3) {
                $endPage = 4; // Show first 4 pages
            } elseif ($currentPage > $totalPages - 2) {
                $startPage = $totalPages - 3; // Show last 4 pages
            } else {
                $startPage = $currentPage - 1; // Show current page and 1 before
                $endPage = $currentPage + 2; // Show current page and 2 after
            }
        }

        // Display page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i < $startPage) {
                continue; // Skip pages before the start page
            }
            if ($i > $endPage) {
                break; // Stop if we've reached the end page
            }
            ?>
            <a href="?page=<?php echo $i; ?>&search=1&ingredient=<?php echo urlencode(implode(',', $_SESSION['ingredients'])); ?>&low_carb=<?php echo $lowCarb; ?>&keto=<?php echo $keto; ?>&mediterranean=<?php echo $mediterranean; ?>" 
               class="pagination-link <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php
        }

        // Add ellipsis if there are more pages
        if ($endPage < $totalPages) {
            echo '<span class="pagination-ellipsis">...</span>';
            echo '<a href="?page=' . $totalPages . '&search=1&ingredient=' . urlencode(implode(',', $_SESSION['ingredients'])) . '&low_carb=' . $lowCarb . '&keto=' . $keto . '&mediterranean=' . $mediterranean . '" class="pagination-link">' . $totalPages . '</a>';
        }
        ?>
        
        <button onclick="location.href='?page=<?php echo min($totalPages, $currentPage + 1); ?>&search=1&ingredient=<?php echo urlencode(implode(',', $_SESSION['ingredients'])); ?>&low_carb=<?php echo $lowCarb; ?>&keto=<?php echo $keto; ?>&mediterranean=<?php echo $mediterranean; ?>'" 
                class="pagination-btn <?php echo ($currentPage === $totalPages) ? 'disabled' : ''; ?>">
            Next &raquo;
        </button>
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
                        <h3>Video Recipe:</h3>
                        <div id="recipeVideo" class="recipe-video"></div>
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

        <div class="chat-button" onclick="toggleChat()">
            <i class="fas fa-robot"></i>
        </div>
        <iframe src="chatbot.php" class="chat-iframe" id="chatFrame"></iframe>
        
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
            const apiKey = "<?php echo $rapidApiKey; ?>"; // RapidAPI key
            const apiHost = "<?php echo $rapidApiHost; ?>"; // RapidAPI host

            // Add error message element after the ingredient search input
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.id = 'ingredient-error';
            errorDiv.textContent = 'This ingredient is already in your list';
            ingredientSearch.parentNode.insertBefore(errorDiv, ingredientSearch.nextSibling);

            // Function to check if ingredient exists
            function ingredientExists(newIngredient) {
                const ingredients = <?php echo json_encode($_SESSION['ingredients']); ?>;
                return ingredients.some(ingredient => 
                    ingredient.toLowerCase() === newIngredient.toLowerCase()
                );
            }

            // Add form submit handler
            document.querySelector('form[action=""]').addEventListener('submit', function(e) {
                const ingredient = ingredientSearch.value.trim();
                const errorMessage = document.getElementById('ingredient-error');
                
                if (ingredientExists(ingredient)) {
                    e.preventDefault();
                    errorMessage.style.display = 'block';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 3000);
                } else {
                    errorMessage.style.display = 'none';
                }
            });

            // Show suggestions based on input
            ingredientSearch.addEventListener('input', () => {
                const query = ingredientSearch.value;

                if (query.length > 1) { // Start searching after a few letters
                    fetch(`https://${apiHost}/food/ingredients/autocomplete?query=${query}&number=10&x-rapidapi-host=${apiHost}&x-rapidapi-key=${apiKey}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestions.innerHTML = ''; // Clear previous suggestions
                            data.forEach(item => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('autocomplete-suggestion');
                                suggestionItem.textContent = item.name;

                                suggestionItem.addEventListener('click', () => {
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
                const apiKey = "<?php echo $rapidApiKey; ?>";
                const apiHost = "<?php echo $rapidApiHost; ?>";

                fetch(`https://${apiHost}/recipes/${recipeId}/information`, {
                    headers: {
                        'x-rapidapi-host': '<?php echo $apiHost; ?>',
                        'x-rapidapi-key': '<?php echo $apiKey; ?>'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Update modal title and image
                        document.getElementById("recipeTitle").textContent = data.title;
                        document.getElementById("recipeImage").src = data.image;

                        // Update ingredients
                        const ingredientsList = document.getElementById("recipeIngredients");
                        ingredientsList.innerHTML = '';
                        data.extendedIngredients.forEach(ingredient => {
                            const li = document.createElement("li");
                            li.textContent = ingredient.original;
                            ingredientsList.appendChild(li);
                        });

                        // Update instructions
                        const instructionsList = document.createElement("ol");
                        if (data.analyzedInstructions && data.analyzedInstructions.length > 0) {
                            data.analyzedInstructions[0].steps.forEach(step => {
                                const li = document.createElement("li");
                                li.textContent = step.step;
                                instructionsList.appendChild(li);
                            });
                        }

                        document.getElementById("recipeInstructions").innerHTML = '';
                        document.getElementById("recipeInstructions").appendChild(instructionsList);

                        // Handle video content
                        const videoContainer = document.getElementById("recipeVideo");
                        videoContainer.innerHTML = ''; // Clear previous video

                        if (data.videos && data.videos.length > 0) {
                            // Use the first video
                            const videoId = data.videos[0].youTubeId;
                            const iframe = document.createElement('iframe');
                            iframe.src = `https://www.youtube.com/embed/${videoId}`;
                            iframe.title = "Recipe Video";
                            iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
                            iframe.allowFullscreen = true;
                            videoContainer.appendChild(iframe);
                        } else {
                            // If no video is available
                            videoContainer.innerHTML = '<div class="video-placeholder">No video available for this recipe</div>';
                        }

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

            function toggleChat() {
                const chatFrame = document.getElementById('chatFrame');
                if (chatFrame.style.display === 'none' || chatFrame.style.display === '') {
                    chatFrame.style.display = 'block';
                } else {
                    chatFrame.style.display = 'none';
                }
            }

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
        </script>
</body>

</html>