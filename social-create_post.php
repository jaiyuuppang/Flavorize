<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .post-container {
            width: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .name {
            margin: 0;
            font-weight: bold;
        }

        .privacy {
            border: none;
            font-size: 12px;
            background-color: transparent;
            color: #0073e6;
            cursor: pointer;
        }

        .post-input {
            width: 100%;
            height: 100px;
            border: none;
            resize: none;
            font-size: 16px;
            margin-bottom: 15px;
            outline: none;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .add-post {
            background-color: #f0f2f5;
            border: none;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .icons span {
            font-size: 18px;
            margin-right: 10px;
            cursor: pointer;
        }

        .next-btn {
            width: 100%;
            padding: 10px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="post-container">
        <div class="header">
            <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
            <div class="user-info">
                <p class="name">Rafael De Leon</p>
                <select class="privacy">
                    <option>Public</option>
                    <option>Friends</option>
                    <option>Only Me</option>
                </select>
            </div>
        </div>
        <textarea class="post-input" placeholder="What's on your mind, Rafael?"></textarea>
        <div class="footer">
            <button class="add-post">Add to your post</button>
            <div class="icons">
                <span>🖼️</span>
                <span>👥</span>
                <span>😊</span>
                <span>GIF</span>
            </div>
        </div>
        <button class="next-btn">Next</button>
    </div>
</body>

</html>