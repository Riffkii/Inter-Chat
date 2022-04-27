<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
</head>
<body>
    <h1>Login</h1>
    <br>
    
    <form action="/user/login" method="post">
        <label for="username">Username: 
            <input type="text" name="username" id="username" autocomplete="off">
        </label>
        </br>
        <label for="password">Password: 
            <input type="password" name="password" id="password" autocomplete="off">
        </label>
        </br>
        <input type="submit" value="Login">
    </form>

    <?php if(isset($model['error'])) { ?>
        <p style="color: red;"><?= $model['error'] ?></p>
    <?php } ?>
</body>
</html>