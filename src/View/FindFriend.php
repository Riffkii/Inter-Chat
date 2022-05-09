<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
</head>
<body>
    <form action="/user/find-friend" method="post">
        <input type="text" name="input" autocomplete="off">
        <input type="submit" name="find" value="Find">
    </form>

    <?php if(isset($model['data'])) { ?>
        <table>
            <?php foreach($model['data'] as $user) { ?>
                <?php $condition = true; ?>
                <tr>
                    <td><?= $user->getName() ?></td>
                    <?php foreach($model['check'] as $check) { ?>
                        <?php if($check->getUsername() == $user->getUsername()) { ?>
                            <td class="button"><input type="button" id="<?= $user->getUsername() ?>" value="Sending Request" disabled="true"></td>
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

    <script>
        function sendData() {
            const conn = new WebSocket("ws://localhost:3000");
            const rows = document.querySelectorAll("td.button");
            for (const row of rows) {
                row.firstChild.onclick = function () {
                    row.firstChild.value = "Sending Request";
                    row.firstChild.disabled = "true";

                    //path (/user/add-friend) ngetrigger method postAddFriend
                    const request = new Request("/user/notifications", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            target: row.firstChild.id ,
                            message: " sending friendships request"
                        })
                    });

                    fetch(request);
                    conn.send(JSON.stringify({
                        target: row.firstChild.id
                    }));
                }
            }
        }

        sendData();
    </script>
</body>
</html>