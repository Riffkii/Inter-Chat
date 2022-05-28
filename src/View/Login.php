<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Login.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>LOGIN</h2>
        </header>
        <main>
            <form action="/user/login" method="post">
                <input type="text" name="username" id="username"
                 autocomplete="off" class="text" placeholder="Username">
                <input type="password" name="password" id="password" 
                autocomplete="off" class="text" placeholder="Password">
                <?php if(isset($model['error'])) { ?>
                    <p style="color: red;"><?= $model['error'] ?></p>
                <?php } else { ?>
                    <p class="error-invisible">e</p>
                <?php } ?>
                <input type="submit" value="Login" class="submit">
            </form>
        </main>
        <footer>
            <p>Don't have an account? Register <a href="/user/register">here</a></p>
        </footer>
    </div>
</body>
</html>