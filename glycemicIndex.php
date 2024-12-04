<?php
$apiKey = "YOUR_SPOONACULAR_API_KEY"; // Replace with your actual API key

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ingredient'])) {
    $ingredient = $_POST['ingredient'];
    
    $url = "https://api.spoonacular.com/food/ingredients/glycemicLoad?apiKey=" . urlencode($apiKey);
    
    $data = [
        'ingredients' => [$ingredient]
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    
    // Use curl instead of file_get_contents for better error handling
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode == 200) {
        $glycemicData = json_decode($response, true);
        
        if (!empty($glycemicData) && isset($glycemicData[0]['glycemicIndex'])) {
            echo $glycemicData[0]['glycemicIndex'];
        } else {
            echo "Not Available";
        }
    } else {
        // Log the error for debugging
        error_log("API Error: HTTP Code " . $httpCode . ", Response: " . $response);
        echo "Error fetching data. Status Code: " . $httpCode;
    }
    
    curl_close($ch);
    exit;
}
?>