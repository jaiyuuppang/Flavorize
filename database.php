<?php
// Check for the presence of the JAWSDB_URL environment variable (Heroku)
$url = getenv("mysql://e6wvr8segyt2gsxq:lfxgbhtuzfkid59v@b4e9xxkxnpu2v96i.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/ob6b06nh9q0ya27d"); // Use JAWSDB_URL instead of the ClearDB URL

if ($url) {
    // Parse the JawsDB URL
    $dbParts = parse_url($url);

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
