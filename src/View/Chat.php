<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/styling.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $model['target'] ?></h1>
        </div>
        <div class="body" id="body">
        </div>
        <div class="footer">
            <form action="/user/chat" id="form" method="GET">
                <input type="text" id="target" value="<?= $model['tUsername'] ?>" style="display: none;">
                <input type="text" class="text" id="input" autocomplete="off">
                <input type="submit" value="Send" id="submit" class="submit">
            </form>
        </div>
    </div>
    <script>
        const conn = new WebSocket("ws://localhost:3000");
        let value = null;

        function event() {
            document.getElementById("submit").onclick = function (event) {
                event.preventDefault();
                fetch('/user/chat', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        target: document.getElementById('target').value,
                        message: document.getElementById('input').value
                    })
                });
                value = document.getElementById('input').value;
                toContainer();
                conn.send(JSON.stringify({
                        target: document.getElementById('target').value,
                        message: document.getElementById('input').value
                    }));
                document.getElementById("form").reset();
            };

            conn.onmessage = function(e) {
                const user = JSON.parse(e.data);
                console.log('test');
                fetch('/user/check-friend?target=' + document.getElementById('target').value, {method: 'GET'})
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if(data.success == "true" && user.message != undefined) {
                            const p = document.createElement('p');
                            p.className = 'another_user';
                            p.innerText = user.message;
                            body.appendChild(p);                
                        }
                    });
            };
        }

        function toContainer() {
            const p = document.createElement('p');
            p.className = 'user';
            p.innerText = value;
            body.appendChild(p);
        }

        function refresh() {
            const row = document.getElementById("body")
            while (row.firstChild) {
                row.removeChild(row.firstChild);
            }
        }

        async function oldDataToContainer(target) {
            refresh();
            const currentUser = await fetch('/user/username', {method: "GET"})
                                        .then(response => response.json());

            fetch('/user/message?target=' + target, {method: 'GET'})
                .then(response => response.json())
                .then(datas => {
                    const body = document.getElementById('body');
                    for (const data of datas) {
                        if(data.fromUser == currentUser.username) {
                            const p = document.createElement('p');
                            p.className = 'user';
                            p.innerText = data.message;
                            body.appendChild(p);
                        } else {
                            const p = document.createElement('p');
                            p.className = 'another_user';
                            p.innerText = data.message;
                            body.appendChild(p);
                        }
                    }
                });
        }

        oldDataToContainer(document.getElementById('target').value);
        event();
    </script>
</body>
</html>