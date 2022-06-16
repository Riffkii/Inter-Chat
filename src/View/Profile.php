<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Profile.css">
</head>
<body>
    <input type="button" value="Back" class="back">
    <div class="container">
        <header>
            <h2>Profile</h2>
        </header>
        <main>
            <a href="/user/cn"><button>Change Name</button></a>
            <a href="/user/cp"><button>Change Password</button></a>
        </main>
    </div>
    <script src="/Assets/JS/Profile.js"></script>
</body>
</html>