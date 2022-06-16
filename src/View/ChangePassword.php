<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Changepassword.css">
</head>
<body>
    <input type="button" value="Back" class="back">
    <div class="container">
        <header>
            <h2>Change Password</h2>
        </header>
        <main>
            <form action="/user/cp" method="post">
                <input type="text" name="op" id="op" autocomplete="off" placeholder="Old Password">
                <input type="text" name="np" id="np" autocomplete="off" placeholder="New Password">
                <?php if(isset($model['error'])) { ?>
                    <p style="color: red;"><?= $model['error'] ?></p>
                <?php } else { ?>
                    <p class="error-invisible">e</p>
                <?php } ?>
                <input type="submit" value="Change" class="submit">
            </form>
        </main>
    </div>
    <script src="/Assets/JS/ChangePassword.js"></script>
</body>
</html>