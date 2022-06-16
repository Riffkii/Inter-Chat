<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Chat.css">
</head>
<body>
    <div class="container">
        <header>
            <input type="button" value="" class="back">
            <h2><?= $model['target'] ?></h2>
        </header>
        <main>
            <p class="in">New Message <span>●</span></p>
            <div class="chat"></div>
        </main>
        <footer>
            <form action="/user/chat" id="form" method="GET">
                <input type="text" id="target" value="<?= $model['tUsername'] ?>" style="display: none;">
                <input type="text" class="text" id="input" autocomplete="off">
                <input type="submit" value="" id="submit" class="submit">
            </form>
        </footer>
    </div>
    <script src="/Assets/JS/Chat.js"></script>
</body>
</html>