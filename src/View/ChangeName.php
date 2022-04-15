<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
</head>
<body>
    <h1>Change Name</h1>
    <br>

    <form action="/user/cn" method="post">
        <label for="cn">New Name: 
            <input type="text" name="cn" id="cn">
        </label>
        
        <input type="submit" value="Change">
    </form>

    <?php if(isset($model['error'])) { ?>
        <p style="color: red;"><?= $model['error'] ?></p>
    <?php } ?>
</body>
</html>