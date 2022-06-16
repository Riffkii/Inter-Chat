<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/ChangeName.css">
</head>
<body>
    <input type="button" value="Back" class="back">
    <div class="container">
        <header>
            <h2>Change Name</h2>
        </header>
        <main>
            <form action="/user/cn" method="post">
                <input type="text" name="cn" id="cn" autocomplete="off" class="text" placeholder="New Name">
                <?php if(isset($model['error'])) { ?>
                    <p style="color: red;"><?= $model['error'] ?></p>
                <?php } else { ?>
                    <p class="error-invisible">e</p>
                <?php } ?>
                <input type="submit" value="Change" class="submit">
            </form>
        </main>
    </div>
    <script src="/Assets/JS/ChangeName.js"></script>
</body>
</html>