<?php
// Database connection details
$host = 'localhost';
$dbname = 'flavorize_db';
$user = 'root';
$password = '';

// Spoonacular API details
$apiKey = 'd1a63598a69d4fcc8b9cdb50ee14532f';
$apiUrl = 'https://api.spoonacular.com/food/ingredients/search';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch ingredients for a specific query
function fetchIngredientsByQuery($apiUrl, $apiKey, $query, $number = 100, $offset = 0) {
    $url = "$apiUrl?apiKey=$apiKey&query=$query&number=$number&offset=$offset";
    
    $response = @file_get_contents($url);

    if ($response === false) {
        throw new Exception("Failed to fetch data for query: $query. Error: " . error_get_last()['message']);
    }

    $data = json_decode($response, true);

    if (isset($data['results']) && !empty($data['results'])) {
        return $data['results'];
    } else {
        return [];
    }
}

// Insert ingredients into the database
function insertIngredients($pdo, $ingredients) {
    $stmt = $pdo->prepare("INSERT INTO ingredients_list (ingredient_id, name) VALUES (:ingredient_id, :name)
                           ON DUPLICATE KEY UPDATE name = :name");

    foreach ($ingredients as $ingredient) {
        try {
            $stmt->execute([
                ':ingredient_id' => $ingredient['id'],
                ':name' => $ingredient['name'],
            ]);
            echo "Inserted: " . $ingredient['name'] . "\n";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
    }
}

// Fetch and process all ingredients by looping through the alphabet
try {
    $alphabet = range('a', 'z'); // Letters to use as query
    foreach ($alphabet as $letter) {
        echo "Fetching ingredients for query: $letter\n";
        
        $offset = 0;
        $batchSize = 100;

        while (true) {
            $ingredients = fetchIngredientsByQuery($apiUrl, $apiKey, $letter, $batchSize, $offset);
            if (empty($ingredients)) {
                break; // Stop when no more results
            }

            insertIngredients($pdo, $ingredients);
            $offset += $batchSize; // Move to the next page
        }
    }

    echo "All ingredients fetched and inserted successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
