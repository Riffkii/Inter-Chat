<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter Chat</title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Dashboard.css">
</head>
<body>
    <div class="container">
        <header>
            <h2><?= $model['name'] ?></h2>
        </header>
        <main>
            <div class="navigation">
                <div>
                    <img src="/Assets/Helper/Image/bell.png" alt="notification">
                    <div class="inv"></div>
                </div>
                <img src="/Assets/Helper/Image/user.png" alt="user-friend">
                <img src="/Assets/Helper/Image/settings.png" alt="settings">
                <div>
                    <ul>
                        <li><a href="/user/find-friend">Find Friend</a></li>
                        <li><a href="/user/friends">Friends</a></li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li><a href="/user/profile">Profile</a></li>
                        <li><a href="/user/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
            <div class="friend">
                <input type="text" autocomplete="off" id="search" placeholder="Search Friend">
                <div><table id="friends"></table></div>
            </div>
        </main>
    </div>
    <script src="/Assets/JS/Dashboard.js"></script>
</body>
</html>