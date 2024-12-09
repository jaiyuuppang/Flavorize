<?php
session_start();
include "database.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    // Check if file is uploaded successfully
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        // Process file upload
        $profile_tmp_name = $_FILES['profile']['tmp_name'];
        $profile_name = $_FILES['profile']['name'];
        $profile_type = $_FILES['profile']['type'];

        // Read the file content
        $profile = file_get_contents($profile_tmp_name);
    } else {
        // Handle error if file upload failed
        echo "<script>alert('Error uploading profile image.'); window.location.href = 'index.php';</script>";
        exit; // Exit script
    }

    // Insert the data into the database
    $sql = "INSERT INTO users (email, first_name, last_name, password, profile) 
            VALUES (?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssss", $email, $first_name, $last_name, $password, $profile);

    // Execute the statement
    if ($stmt->execute()) {
        // Display success alert and redirect
        echo "<script>alert('New user added successfully!'); window.location.href = 'indexUser.php';</script>";
    } else {
        // Display error alert and redirect
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "'); window.location.href = 'index.php';</script>";
    }

    // Close statement
    $stmt->close();
}
exit();
?>
