<?php
// Spoonacular API key
$api_key = "53b3a41b5cf54b97845a3945e00158f3";

// API URL to fetch Mediterranean recipes with nutrition
$api_url = "https://api.spoonacular.com/recipes/complexSearch?cuisine=mediterranean&apiKey=$api_key&number=150&addRecipeNutrition=true";

// Fetch data from Spoonacular
$response = file_get_contents($api_url);
$data = json_decode($response, true);

if ($data && isset($data['results'])) {
    // Connect to MySQL database
    $conn = new mysqli("localhost", "root", "", "flavorize_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Iterate through the recipes and fetch detailed information
    foreach ($data['results'] as $recipe) {
        $id = $recipe['id'];
        $title = $conn->real_escape_string($recipe['title']);
        $image = $conn->real_escape_string($recipe['image']);
        $calories = $recipe['nutrition']['nutrients'][0]['amount'] ?? 0; // Calories
        $protein = $recipe['nutrition']['nutrients'][1]['amount'] ?? 0; // Protein
        $fat = $recipe['nutrition']['nutrients'][2]['amount'] ?? 0; // Fat
        $carbs = $recipe['nutrition']['nutrients'][3]['amount'] ?? 0; // Carbohydrates

        // Fetch detailed recipe information for instructions, meal type, and ingredients
        $recipe_info_url = "https://api.spoonacular.com/recipes/$id/information?apiKey=$api_key";
        $recipe_info_response = file_get_contents($recipe_info_url);
        $recipe_info = json_decode($recipe_info_response, true);

        $instructions = "";
        if (isset($recipe_info['analyzedInstructions'][0]['steps'])) {
            foreach ($recipe_info['analyzedInstructions'][0]['steps'] as $step) {
                $instructions .= $conn->real_escape_string($step['step']) . " ";
            }
        }

        // Fetch the meal type(s)
        $meal_type = "";
        if (isset($recipe_info['dishTypes'])) {
            $meal_type = $conn->real_escape_string(implode(", ", $recipe_info['dishTypes']));
        }

        // Fetch the ingredients list
        $ingredients = "";
        if (isset($recipe_info['extendedIngredients'])) {
            $ingredient_names = array_map(function($ingredient) {
                return $ingredient['original']; // Fetch original string of each ingredient
            }, $recipe_info['extendedIngredients']);
            $ingredients = $conn->real_escape_string(implode(", ", $ingredient_names));
        }

        // Check if the recipe already exists in the database
        $check_sql = "SELECT COUNT(*) FROM recipes WHERE recipe_id = '$id'";
        $check_result = $conn->query($check_sql);
        $check_row = $check_result->fetch_row();

        if ($check_row[0] == 0) {
            // Recipe doesn't exist, insert it
            $sql = "INSERT INTO recipes (recipe_id, title, image, calories, protein, fat, carbs, cuisine, instructions, meal_type, ingredients) 
                    VALUES ('$id', '$title', '$image', '$calories', '$protein', '$fat', '$carbs', 'Mediterranean', '$instructions', '$meal_type', '$ingredients')";
            if ($conn->query($sql) === TRUE) {
                echo "Recipe '$title' with nutrition, instructions, meal type '$meal_type', and ingredients added successfully!<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Recipe already exists, skip it
            echo "Recipe '$title' is already in the database, skipping...<br>";
        }
    }

    $conn->close();
} else {
    echo "Failed to fetch recipes.";
}
?>
