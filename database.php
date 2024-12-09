<?php
// Get the JAWSDB_URL environment variable (Heroku)
$url = getenv("JAWSDB_URL"); // This is the proper way to get the JAWSDB URL

if ($url) {
    // Parse the JawsDB URL
    $dbParts = parse_url($url);

    // Extract the individual components from the URL
    $servername = $dbParts['host'];  // Extract the host
    $username = $dbParts['user'];    // Extract the username
    $password = $dbParts['pass'];    // Extract the password
    $database = ltrim($dbParts['path'], '/');  // Extract the database name and remove the leading '/'
} else {
    // Fallback to local development settings if not on Heroku
    $servername = "localhost";  // Localhost for local development
    $username = "root";         // Local database username
    $password = "";             // Local database password
    $database = "flavorize";    // Local database name
}

// Create a new MySQL connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
