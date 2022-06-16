<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Friends.css">
</head>
<body>
    <input type="button" value="Back" class="back">
    <div class="container">
        <header>
            <h2><?= $model['title'] ?></h2>
        </header>
        <main>
            <div class="search-bar">
                <input type="text" autocomplete="off" id="search" placeholder="Name">
            </div>
            <div class="friends">
                <table id="friends"></table>
            </div>
        </main>
    </div>
    <script src="/Assets/JS/Friends.js"></script>
</body>
</html>