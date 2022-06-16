<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/FindFriend.css">
</head>
<body>
    <input type="button" value="Back" class="back">
    <div class="container">
        <header>
            <h2><?= $model['title'] ?></h2>
        </header>
        <main>
            <div class="search-bar">
                <form action="/user/find-friend" method="post">
                    <input type="text" name="input" autocomplete="off" class="text" placeholder="Name">
                    <input type="submit" name="find" value="Find" class="submit">
                </form>
            </div>
            <div class="users">
                <?php if(isset($model['data'])) { ?>
                    <table>
                        <?php foreach($model['data'] as $user) { ?>
                            <?php $condition = true; ?>
                            <tr>
                                <td><?= $user->getName() ?></td>
                                <?php foreach($model['check'] as $check) { ?>
                                    <?php if($check->getUsername() == $user->getUsername()) { ?>
                                        <td class="button"><input type="button" id="<?= $user->getUsername() ?>" value="Sending Request" disabled="true" class="sending"></td>
                                    <?php $condition = false; ?>
                                    <?php break; } ?>
                                <?php } ?>
                                
                                <?php if($condition == true) { ?>
                                    <td class="button"><input type="button" id="<?= $user->getUsername() ?>" value="Add"></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
        </main>
    </div>
    <script src="/Assets/JS/FindFriend.js"></script>
</body>
</html>