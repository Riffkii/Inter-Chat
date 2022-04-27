<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
</head>
<body>
    <h1>Change Password</h1>
    <br>

    <form action="/user/cp" method="post">
        <label for="op">Old Name: 
            <input type="text" name="op" id="op" autocomplete="off">
        </label>
        <br>
        <label for="np">New Name: 
            <input type="text" name="np" id="np" autocomplete="off">
        </label>
        <br>
        <input type="submit" value="Change">
    </form>

    <?php if(isset($model['error'])) { ?>
        <p style="color: red;"><?= $model['error'] ?></p>
    <?php } ?>
</body>
</html>