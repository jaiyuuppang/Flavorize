<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            height: 100%;
        }

        .container {
            max-width: 100%;
            margin: 0 15px 0 15px;
            padding: 25px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: auto;
            max-height: 760px;
        }

        h1 {
            text-align: center;
            margin: 60px 0 30px;
            font-weight: bold;
            font-size: 30px;
        }

        .table-container {
            width: 100%;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .staff-photo {
            max-width: 100%;
            height: 50px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .editButton,
        .deleteButton {
            max-width: 100%;
            height: 30px;
            border: none;
            background: none;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .editButton:hover,
        .deleteButton:hover {
            transform: scale(1.2);
        }

        .editButton {
            color: #4CAF50;
            margin-right: 40px;
            /* Green color for edit button */
        }

        .deleteButton {
            color: #f44336;
            /* Red color for delete button */
        }

        .addButton {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #f08a22;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .addButton:hover {
            background-color: #e07b1b;
        }

        /* Overlay background (dimmed effect) */
        .form-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Semi-transparent black */
            z-index: 999;
            /* Below the form popup */
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        /* When form is active (overlay becomes visible) */
        .form-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Initially hide the form */
        .form-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 430px;
            max-width: 420px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease, transform 0.3s ease;
            /* Smooth animation */
        }

        /* When form is visible (this will be toggled via JavaScript) */
        .form-container.active {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%);
            animation: slideIn 0.5s ease-out forwards;
        }

        /* X mark inside the form */
        .form_close {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #333;
            z-index: 1001;
        }

        .input_box {
            position: relative;
            margin-top: 30px;
            width: 100%;
            height: 40px;
        }

        .input_box input {
            height: 100%;
            width: 100%;
            border: none;
            outline: none;
            padding: 0 30px;
            color: #333;
            transition: all 0.2s ease;
            border-bottom: 1.5px solid #aaaaaa;
        }

        .input_box input:focus {
            border-color: rgb(250, 120, 69);
        }

        .input_box i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #707070;
        }

        .input_box i.email,
        .input_box i.password,
        .input_box i.firstName,
        .input_box i.lastName,
        .input_box i.profile {
            left: 0;
        }

        .input_box input:focus~i.email,
        .input_box input:focus~i.password,
        .input_box input:focus~i.firstName,
        .input_box input:focus~i.lastName,
        .input_box input:focus~i.profile {
            color: rgb(250, 120, 69);
        }

        .input_box i.pw_hide {
            right: 0;
            font-size: 18px;
            cursor: pointer;
        }

        .option_field {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-container a {
            color: rgb(250, 120, 69);
            font-size: 12px;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        .checkbox {
            display: flex;
            white-space: nowrap;
            column-gap: 8px;
        }

        .checkbox input {
            accent-color: rgb(250, 120, 69);
        }

        .checkbox label {
            font-size: 12px;
            color: #0b0217;
            cursor: pointer;
            user-select: none;
        }

        .form-container .loginBtn {
            background-color: rgb(250, 120, 69);
            margin-top: 30px;
            width: 100%;
            padding: 10px 0;
            border-radius: 10px;
        }

        .login_signup {
            font-size: 12px;
            text-align: center;
            margin-top: 15px;
        }

        .loginBtn {
            padding: 6px 24px;
            border: 1px solid rgb(252, 196, 124);
            background-color: rgb(253, 77, 8);
            border-radius: 6px;
            color: aliceblue;
            cursor: pointer;
            font-weight: bold;
        }

        .loginBtn:active {
            transform: scale(0.98);
        }

        .form-container h2 {
            font-size: 22px;
            color: #0b0217;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="addButton" id="form-open"><i class="fa-solid fa-plus"></i></button>
        <table>
            <thead>
                <th>ID #</th>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Profile</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                include "database.php";
                $query = "SELECT * FROM users WHERE userType = 'Admin'";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['userType'] . "</td>";
                        $profileImage = base64_encode($row['profile']);
                        echo "<td><img class='staff-photo' src='data:image/jpeg;base64,{$profileImage}' alt='Profile Image'></td>";
                        echo "<td><button class='deleteButton' onclick=\"deleteStaff(" . $row['id'] . ")\"><i class='fa-solid fa-trash'></i></button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No staff found.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>

        <div class="form-overlay"></div>
        <div class="form-container" id="form-container">
            <i class="fa-solid fa-xmark form_close" id="form-close"></i>

            <div class="form login-form active">
                <form action="add-adminDB.php" method="post" enctype="multipart/form-data">
                    <h2>Add new Admin</h2>

                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter email" required>
                        <i class="fa-solid fa-envelope email"></i>
                    </div>
                    <div class="input_box">
                        <input type="first_name" name="first_name" placeholder="Enter first name" required>
                        <i class="fa-solid fa-user firstName"></i>
                    </div>
                    <div class="input_box">
                        <input type="last_name" name="last_name" placeholder="Enter last name" required>
                        <i class="fa-solid fa-user lastName"></i>
                    <div class="input_box">
                        <input type="file" name="profile" accept="image/*" placeholder="Enter photo" required>
                        <i class="fa-regular fa-image profile"></i>
                    </div>
                    <input type="hidden" name="userType" value="Admin">

                    <button type="submit" class="loginBtn">Add Admin</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const formContainer = document.querySelector('.form-container');
        const openFormButton = document.querySelector('#form-open');
        const closeFormButton = document.querySelector('.form_close');
        const formOverlay = document.querySelector('.form-overlay');
        const loginForm = document.querySelector('.login-form');
        const signupForm = document.querySelector('.signup-form');
        const showSignup = document.querySelector('.show-signup');
        const showLogin = document.querySelector('.show-login');

        // Function to open the form and show the overlay
        openFormButton.addEventListener('click', () => {
            formContainer.classList.add('active');  // Show form
            formOverlay.classList.add('active');    // Show overlay
        });

        // Function to close the form and hide the overlay
        closeFormButton.addEventListener('click', () => {
            formContainer.classList.remove('active');  // Hide form
            formOverlay.classList.remove('active');    // Hide overlay
        });

        // Close form when clicking on the overlay
        formOverlay.addEventListener('click', () => {
            formContainer.classList.remove('active');  // Hide form
            formOverlay.classList.remove('active');    // Hide overlay
        });

        // Prevent closing when clicking inside the form container itself
        formContainer.addEventListener('click', (e) => {
            e.stopPropagation();  // Prevent click event from propagating to the overlay
        });

        showSignup.addEventListener('click', function (e) {
            e.preventDefault();
            loginForm.style.display = 'none';  // Hide login form
            signupForm.style.display = 'block'; // Show signup form
        });

        showLogin.addEventListener('click', function (e) {
            e.preventDefault();
            signupForm.style.display = 'none'; // Hide signup form
            loginForm.style.display = 'block'; // Show login form
        });

        function togglePassword(fieldId, icon) {
            const passwordField = document.getElementById(fieldId);

            // Toggle the type attribute between "password" and "text"
            if (passwordField.type === 'password') {
                passwordField.type = 'text'; // Show password
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password'; // Hide password
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this Admin?')) {
                let formData = new FormData();
                formData.append('id', id);

                fetch('delete-adminDB.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            alert('Admin deleted successfully.');
                            location.reload();
                        } else {
                            console.error('Error:', data);
                            alert('Error deleting Admin: ' + data);
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        }
    </script>
</body>

</html>