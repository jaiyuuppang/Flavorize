<?php
if (isset($_POST['recipe_id'])) {
    $recipeId = $_POST['recipe_id'];
    $apiKey = "26a352d502294212b749570130ce9ff8"; // Your Spoonacular API key

    // Fetch recipe details from Spoonacular API
    $apiUrl = "https://api.spoonacular.com/recipes/$recipeId/information?apiKey=$apiKey";
    $response = file_get_contents($apiUrl);
    $recipeDetails = json_decode($response, true);

    if ($recipeDetails) {
        echo "<h1>" . htmlspecialchars($recipeDetails['title']) . "</h1>";
        echo "<img src='" . htmlspecialchars($recipeDetails['image']) . "' alt='" . htmlspecialchars($recipeDetails['title']) . "'>";
        echo "<p>Servings: " . htmlspecialchars($recipeDetails['servings']) . "</p>";
        echo "<p>Cooking Time: " . htmlspecialchars($recipeDetails['readyInMinutes']) . " minutes</p>";
        echo "<h2>Ingredients:</h2><ul>";
        foreach ($recipeDetails['extendedIngredients'] as $ingredient) {
            echo "<li>" . htmlspecialchars($ingredient['original']) . "</li>";
        }
        echo "</ul>";
        echo "<h2>Instructions:</h2><p>" . htmlspecialchars($recipeDetails['instructions']) . "</p>";
    } else {
        echo "<p>Recipe details could not be retrieved. Please try again later.</p>";
    }
} else {
    echo "<p>No recipe ID provided.</p>";
}
?>