<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
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
            overflow: hidden;
        }

        .buttons {
            display: flex;
            margin: 20px;
        }

        .buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #f08a22;
            border: none;
            border-radius: 50px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .buttons button:hover {
            background-color: #e07b1b;
        }

        .iframe-container {
            margin-top: 20px;
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .iframe-container iframe {
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

    <div class="buttons">
        <button onclick="loadIframe('add-staff.php')">Staff</button>
        <button onclick="loadIframe('staff-category.php')">Staff Category</button>
    </div>

    <div class="iframe-container">
        <iframe id="contentFrame" src="add-admin.php"></iframe>
    </div>

    <script>
        function loadIframe(url) {
            document.getElementById('contentFrame').src = url;
        }
    </script>

</body>

</html>
