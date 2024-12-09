<?php
include "database.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $userType = $row['userType'];
    $id = $row['id'];

    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['id'] = $id;
    $_SESSION['userType'] = $userType;

    if ($userType === 'Admin') {
        header("Location: admin-dashboard.php");
        exit();
    } else if ($userType === 'user') {
        header("Location: aboutUser.php");
        exit();
    }
} else {
    // This block will run if no match is found
    echo "<script>alert('Invalid email and/or password. Please try again.'); window.location.href = 'about.php';</script>";
}
$stmt->close();
$conn->close();
?>
