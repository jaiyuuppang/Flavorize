<?php
session_start();
include "database.php";

if (!isset($_SESSION['id'])) {
    // Redirect to login if session ID is not set
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];

// Prepare the SQL statement to prevent SQL injection
$sql = $conn->prepare("SELECT first_name, last_name, userType, profile FROM users WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $userType = $row['userType'];
    $profile = $row['profile'];
} else {
    echo "User not found.";
    exit();
}
$sql->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        :root {
            --body-color: #e4e9f7;
            --sidebar-color: #fff;
            --primary-color: #695cfe;
            --primary-color-light: #f6f5ff;
            --toggle-color: #ddd;
            --text-color: #707070;

            --tran-02: all 0.2s ease;
            --tran-03: all 0.3s ease;
            --tran-04: all 0.4s ease;
            --tran-05: all 0.5s ease;
        }

        body {
            height: 100vh;
            background: var(--body-color);
            transition: var(--tran-04);
            overflow: hidden;
        }

        body.dark {
            --body-color: #18191a;
            --sidebar-color: #242526;
            --primary-color: #3a3b3c;
            --primary-color-light: #3a3b3c;
            --toggle-color: #fff;
            --text-color: #ccc;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            padding: 10px 14px;
            background: var(--sidebar-color);
            transition: var(--tran-04);
            z-index: 1000;
        }

        .sidebar.close {
            width: 88px;
        }

        /* ===== Reusable CSS ===== */
        .sidebar .text {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-color);
            transition: var(--tran-04);
            white-space: nowrap;
            opacity: 1;
        }

        .sidebar.close .text {
            opacity: 0;
        }

        .sidebar .image {
            min-width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar li {
            height: 50px;
            margin-top: 10px;
            list-style: none;
            display: flex;
            align-items: center;
        }

        .sidebar li .icon {
            display: flex;
            align-items: center;
            min-width: 60px;
            font-size: 20px;
            justify-content: center;
        }

        .sidebar li .icon,
        .sidebar li .text {
            color: var(--text-color);
            transition: var(--tran-04);
        }

        .sidebar header {
            position: relative;
        }

        .sidebar .image-text img {
            width: 40px;
            border-radius: 6px;
        }

        .sidebar header .image-text {
            display: flex;
            align-items: center;
        }

        header .image-text .header-text {
            display: flex;
            flex-direction: column;
        }

        .header-text .name {
            font-weight: 600;
        }

        .header-text .profession {
            margin-top: -2px;
        }

        .sidebar header .toggle {
            position: absolute;
            top: 50%;
            right: -25px;
            transform: translateY(-50%) rotate(180deg);
            height: 25px;
            width: 25px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--sidebar-color);
            font-size: 22px;
            transition: var(--tran-04);
            cursor: pointer;
        }

        .sidebar.close header .toggle {
            transform: translateY(-50%);
        }

        body.dark .sidebar header .toggle {
            color: var(--text-color);
        }

        .sidebar .menu {
            margin-top: 35px;
        }

        .sidebar .search-box {
            background: var(--primary-color-light);
            border-radius: 6px;
            transition: var(--tran-03);
        }

        .search-box input {
            height: 100%;
            width: 100%;
            outline: none;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            background: var(--primary-color-light);
            color: var(--text-color);
        }

        .sidebar li a {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 6px;
            transition: var(--tran-04);
        }

        .sidebar li a:hover {
            background: var(--primary-color);
        }

        .sidebar li a:hover .icon,
        .sidebar li a:hover .text {
            color: var(--sidebar-color);
        }

        body.dark .sidebar li a:hover .icon,
        body.dark .sidebar li a:hover .text {
            color: var(--text-color);
        }

        .sidebar .menu-bar {
            height: calc(100% - 50px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .main--content {
            position: relative;
            background: #ebe9e9;
            margin-left: 250px;
            /* Adjust to match the sidebar width */
            width: calc(100% - 250px);
            /* Adjust width based on sidebar */
            padding: 1rem;
            transition: var(--tran-04);
        }

        .sidebar.close~.main--content {
            margin-left: 88px;
            /* Matches the sidebar's close state */
            width: calc(100% - 88px);
        }


        .iframe-container {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%;
            /* 16:9 aspect ratio (responsive iframe) */
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" alt="logo">
                </span>

                <div class="text header-text">
                    <span
                        class="name"><?php echo htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name); ?></span>
                    <span class="profession"><?php echo htmlspecialchars($userType); ?></span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="users.php">
                            <i class='bx bxs-color icon'></i>
                            <span class="text nav-text">Admin</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-bowl-rice icon'></i>
                            <span class="text nav-text">Food Recipe</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="index.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav>
    <div class="main--content">
        <div class="iframe-container">
            <iframe name="iframe" src="add-admin.php" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        const body = document.querySelector("body"),
            sidebar = document.querySelector(".sidebar"),
            toggle = document.querySelector(".toggle"),
            searchBtn = document.querySelector(".search-box"),
            modeSwitch = document.querySelector(".toggle-switch"),
            modeText = document.querySelector(".mode-text");

        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        })

        searchBtn.addEventListener("click", () => {
            sidebar.classList.remove("close");
        })
    </script>
</body>

</html>