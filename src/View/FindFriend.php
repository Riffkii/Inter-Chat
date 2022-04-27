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
                <tr>
                    <td><?= $user->getName() ?></td>
                    <td class="button"><input type="button" id="<?= $user->getUsername() ?>" value="Add"></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <script>
        const rows = document.querySelectorAll("td.button");
        for (const row of rows) {
            row.firstChild.onclick = function () {
                row.firstChild.value = "Sending Request";
                const data = JSON.stringify({
                        target: row.firstChild.id 
                    })

                //path (/user/add-friend) ngetrigger method postAddFriend
                const request = new Request("/user/add-friend", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        target: row.firstChild.id 
                    })
                });

                const response = fetch(request);
            }
        }
    </script>
</body>
</html>