<?php
// Define the path to your SQL file
$filePath = "recipes.sql";

// Open the file
$ingredients = [];
try {
    $file = fopen($filePath, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            // Check if the line contains "ingredient" and is an INSERT statement
            if (stripos($line, "ingredient") !== false && stripos($line, "INSERT") !== false) {
                // Extract the part of the line with values
                $startIndex = stripos($line, "VALUES") + strlen("VALUES");
                $valuesPart = trim(substr($line, $startIndex), " ();");
                // Split into individual rows
                $rows = explode("),(", $valuesPart);
                foreach ($rows as $row) {
                    // Assuming the format: (id, 'ingredient_name', ...)
                    $columns = explode(",", $row);
                    // Adjust the index if necessary based on your schema
                    $ingredientName = trim($columns[1], " '\"");
                    $ingredients[$ingredientName] = true; // Use associative array to avoid duplicates
                }
            }
        }
        fclose($file);
    }
    
    // Generate INSERT statements
    $outputFile = "extracted_ingredients.sql";
    $output = fopen($outputFile, "w");
    if ($output) {
        foreach (array_keys($ingredients) as $ingredient) {
            fwrite($output, "INSERT INTO ingredients (name) VALUES ('" . addslashes($ingredient) . "');\n");
        }
        fclose($output);
        echo "Extracted ingredients saved to $outputFile.";
    } else {
        echo "Error: Unable to create output file.";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
