<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter Chat</title>
</head>
<body>
    <h1>Welcome <?= $model['name'] ?></h1>
    <br>

    <a href="/user/notification"><button>Notification<?= $model['notification']?></button></a>
    <a href="/user/find-friend"><button>Search Friend</button></a>
    <a href="/user/friends"><button>Friends</button></a>
    <a href="/user/profile"><button>Profile</button></a>
    <a href="/user/logout"><button>Logout</button></a>
    <br>
    <br>

    <label for="">Search: <input type="text" autocomplete="off" id="search"></label>
</body>
</html>