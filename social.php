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
$sql = $conn->prepare("SELECT * FROM users WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profile = $row['profile'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $username = $row['username'];
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
    <title>Social</title>
    <link rel="stylesheet" href="social.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <nav>
        <div class="container">
            <h2 class="log">
                FlavorVibes
            </h2>
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="" id="" placeholder="search">
            </div>
            <div class="create">
                <label class="btn btn-primary" for="create-post" onclick="window.location.href='foodrecipeUser.php'">Food
                    Recipe</label>
                <div class="profile-photo">
                    <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image">
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="left">
                <a class="profile">
                    <div class="profile-photo">
                        <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image">
                    </div>
                    <div class="handle">
                        <h4><?php echo $first_name . ' ' . $last_name; ?></h4> <!-- Display first and last name -->
                        <p class="text-muted">
                            @<?php echo $username; ?> <!-- Display username -->
                        </p>
                    </div>
                </a>
                <div class="sidebar">
                    <a class="menu-item active">
                        <span><i class="fa-solid fa-house"></i></span>
                        <h3>Home</h3>
                    </a>
                    <a class="menu-item">
                        <span><i class="fa-solid fa-bookmark"></i></span>
                        <h3>Bookmark</h3>
                    </a>
                    <a class="menu-item" id="notifications">
                        <span><i class="fa-solid fa-bell"><small class="notification-count">9+</small></i></span>
                        <h3>Notications</h3>
                        <div class="notifications-popup">
                            <div>
                                <div class="profile-photo">
                                    <img src="./images/developer2.jpg" alt="Profile Picture">
                                </div>
                                <div class="notification-body">
                                    <b>Jaihra Maribbay</b> Accepted your friend request
                                    <small class="text-muted">2 DAYS AGO</small>
                                </div>
                            </div>
                            <div>
                                <div class="profile-photo">
                                    <img src="./images/developer3.png" alt="Profile Picture">
                                </div>
                                <div class="notification-body">
                                    <b>Jhen Molina</b> Commented on your post
                                    <small class="text-muted">3 DAYS AGO</small>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a class="menu-item" id="messages-notifications">
                        <span><i class="fa-solid fa-envelope"><small class="notification-count">6</small></i></span>
                        <h3>Messages</h3>
                    </a>
                    <a class="menu-item">
                        <span><i class="fa-solid fa-gear"></i></span>
                        <h3>Settings</h3>
                    </a>
                </div>
                <label for="create-post" class="btn btn-primary">Create Post</label>
            </div>

            <div class="middle">
                <form action="#" class="create-post">
                    <div class="profile-photo">
                        <img src="data:image/png;base64,<?php echo base64_encode($profile); ?>" class="profile_image">
                    </div>
                    <input type="text" placeholder="What's on your mind, <?php echo $first_name; ?>?" id="create-post">
                    <input type="submit" value="Post" class="btn btn-primary">
                </form>

                <div class="feeds">
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/developer2.jpg" alt="">
                                </div>
                                <div class="ingo">
                                    <h3>Jaihra Maribbay</h3>
                                    <small>Dubai, 15 MINUTES AGO</small>
                                </div>
                            </div>
                            <span class="edit"><i class="fa-solid fa-ellipsis"></i></span>
                        </div>

                        <div class="photo">
                            <img src="./images/item3.jpg" alt="">
                        </div>

                        <div class="action-button">
                            <div class="interaction-buttons">
                                <span><i class="fa-regular fa-heart"></i></span>
                                <span><i class="fa-regular fa-comment-dots"></i></span>
                                <span><i class="fa-regular fa-share-from-square"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="fa-regular fa-bookmark"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/developer1.jpg" alt=""></span>
                            <span><img src="./images/developer2.jpg" alt=""></span>
                            <span><img src="./images/developer3.png" alt=""></span>
                            <p>Liked by <b>Rafael De Leon</b> and <b>2,323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Jaihra Maribbay</b> Lorem ipsum dolor sit amet consectetur adipisicing elit. <span
                                    class="hash-tag">#YummyFood</span></p>
                        </div>
                        <div class="comments text-muted">
                            View all 237 comments
                        </div>
                    </div>

                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/developer3.png" alt="">
                                </div>
                                <div class="ingo">
                                    <h3>Jhen Molina</h3>
                                    <small>Singapore, 2 HOURS AGO</small>
                                </div>
                            </div>
                            <span class="edit"><i class="fa-solid fa-ellipsis"></i></span>
                        </div>

                        <div class="photo">
                            <img src="./images/plate4.jpg" alt="">
                        </div>

                        <div class="action-button">
                            <div class="interaction-buttons">
                                <span><i class="fa-regular fa-heart"></i></span>
                                <span><i class="fa-regular fa-comment-dots"></i></span>
                                <span><i class="fa-regular fa-share-from-square"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="fa-regular fa-bookmark"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/developer1.jpg" alt=""></span>
                            <span><img src="./images/developer2.jpg" alt=""></span>
                            <span><img src="./images/developer3.png" alt=""></span>
                            <p>Liked by <b>Rafael De Leon</b> and <b>6,713 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Jhen Molina</b> Lorem ipsum dolor sit amet consectetur adipisicing elit. <span
                                    class="hash-tag">#YummyFood</span></p>
                        </div>
                        <div class="comments text-muted">
                            View all 2378 comments
                        </div>
                    </div>

                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/developer1.jpg" alt="">
                                </div>
                                <div class="ingo">
                                    <h3>Rafael De Leon</h3>
                                    <small>Philippines, 1 DAY AGO</small>
                                </div>
                            </div>
                            <span class="edit"><i class="fa-solid fa-ellipsis"></i></span>
                        </div>

                        <div class="photo">
                            <img src="./images/food.jpg" alt="">
                        </div>

                        <div class="action-button">
                            <div class="interaction-buttons">
                                <span><i class="fa-regular fa-heart"></i></span>
                                <span><i class="fa-regular fa-comment-dots"></i></span>
                                <span><i class="fa-regular fa-share-from-square"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="fa-regular fa-bookmark"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/developer1.jpg" alt=""></span>
                            <span><img src="./images/developer2.jpg" alt=""></span>
                            <span><img src="./images/developer3.png" alt=""></span>
                            <p>Liked by <b>Jhen Molina</b> and <b>323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Rafael De Leon</b> Lorem ipsum dolor sit amet consectetur adipisicing elit. <span
                                    class="hash-tag">#YummyFood</span></p>
                        </div>
                        <div class="comments text-muted">
                            View all 27 comments
                        </div>
                    </div>
                </div>
            </div>

            <div class="right">

                <div class="messages">
                    <div class="heading">
                        <h4>Messages</h4> <i class="fa-solid fa-ellipsis"></i>
                    </div>

                    <!---------- SEARCH BAR ---------->
                    <div class="search-bar">
                        <i class="fa-solid fa-search"></i>
                        <input type="search" placeholder="Search messages" id="message-search">
                    </div>

                    <!---------- MESSAGES CATEGORY ---------->
                    <div class="category">
                        <h6 class="active">Primary</h6>
                        <h6>General</h6>
                        <h6 class="message-requests">Requests(7)</h6>
                    </div>

                    <!---------- MESSAGE ---------->
                    <div class="message">

                        <div class="profile-photo">
                            <img src="./images/developer2.jpg">
                        </div>
                        <div class="message-body">
                            <h5>Jaihra Maribbay</h5>
                            <p class="text-muted">Just woke up bruh</p>
                        </div>
                    </div>
                    <!---------- MESSAGE ---------->
                    <div class="message">
                        <div class="profile-photo">
                            <img src="./images/developer3.png">
                        </div>
                        <div class="message-body">
                            <h5>Jhen Molina</h5>
                            <p class="text-bold">2 new messages</p>
                        </div>
                    </div>
                    <!---------- MESSAGE ---------->
                    <div class="message">
                        <div class="profile-photo">
                            <img src="./images/developer2.jpg">
                            <div class="active"></div>
                        </div>
                        <div class="message-body">
                            <h5>Jaihra Maribbay</h5>
                            <p class="text-bold">Happy Birthday!!</p>
                        </div>
                    </div>
                    <!---------- MESSAGE ---------->
                    <div class="message">
                        <div class="profile-photo">
                            <img src="./images/developer3.png">
                            <div class="active"></div>
                        </div>
                        <div class="message-body">
                            <h5>Jhen Molina</h5>
                            <p class="text-bold">2 new messages</p>
                        </div>
                    </div>
                </div>

                <div class="friend-requests">
                    <h4>Requests</h4>
                    <div class="request">
                        <div class="info">
                            <div class="profile-photo">
                                <img src="./images/developer3.png" alt="">
                            </div>
                            <div>
                                <h5>Jhen Molina</h5>
                                <p class="text-muted">
                                    3 mutual friends
                                </p>
                            </div>
                        </div>
                        <div class="action">
                            <button class="btn btn-primary">Accept</button>
                            <button class="btn">Decline</button>
                        </div>
                    </div>
                    <div class="request">
                        <div class="info">
                            <div class="profile-photo">
                                <img src="./images/developer2.jpg" alt="">
                            </div>
                            <div>
                                <h5>Jaihra Maribbay</h5>
                                <p class="text-muted">
                                    2 mutual friends
                                </p>
                            </div>
                        </div>
                        <div class="action">
                            <button class="btn btn-primary">Accept</button>
                            <button class="btn">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // SIDEBAR
        const menuItems = document.querySelectorAll('.menu-item');

        // MESSAGES
        const messagesNotification = document.querySelector('#messages-notifications');
        const messages = document.querySelector('.messages');
        const message = messages.querySelectorAll('.message');
        const messageSearch = document.querySelector('#message-search');

        // remove active class from all menu items
        const changeActiveItem = () => {
            menuItems.forEach(item => {
                item.classList.remove('active');
            });
        }

        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                changeActiveItem();
                item.classList.add('active');
                if (item.id != 'notifications') {
                    document.querySelector('.notifications-popup').style.display = 'none';
                } else {
                    document.querySelector('.notifications-popup').style.display = 'block';
                    document.querySelector('#notifications .notification-count').style.display = 'none';
                }
            });
        });

        const searchMessage = () => {
            const val = messageSearch.value.toLowerCase();
            message.forEach(chat => {
                let name = chat.querySelector('h5').textContent.toLowerCase();
                if (name.indexOf(val) != -1) {
                    chat.style.display = 'flex';
                } else {
                    chat.style.display = 'none';
                }
            })
        }

        messageSearch.addEventListener('keyup', searchMessage)

        messagesNotification.addEventListener('click', () => {
            messages.style.boxShadow = '0 0 1rem var(--color-primary)';
            messagesNotification.querySelector('.notification-count').style.display = 'none';
            setTimeout(() => {
                messages.style.boxShadow = 'none';
            }, 2000);
        })
    </script>
</body>

</html>