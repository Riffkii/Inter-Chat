<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Chat.css">
</head>
<body>
    <div class="container">
        <header>
            <input type="button" value="" class="back">
            <h2><?= $model['target'] ?></h2>
        </header>
        <main></main>
        <footer>
            <form action="/user/chat" id="form" method="GET">
                <input type="text" id="target" value="<?= $model['tUsername'] ?>" style="display: none;">
                <input type="text" class="text" id="input" autocomplete="off">
                <input type="submit" value="" id="submit" class="submit">
            </form>
        </footer>
    </div>
    <script>
        const conn = new WebSocket("ws://localhost:3000");
        const body = document.querySelector(".container main");
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
                if(conn.readyState === WebSocket.OPEN) {
                    conn.send(JSON.stringify({
                        target: document.getElementById('target').value,
                        message: document.getElementById('input').value
                    }));
                }
                document.getElementById("form").reset();
                document.querySelector('.container main p:last-child').scrollIntoView(true);
            };

            conn.onmessage = function(e) {
                const user = JSON.parse(e.data);
                console.log('test');
                fetch('/user/check-friend?target=' + document.getElementById('target').value, {method: 'GET'})
                    .then(response => response.json())
                    .then(data => {
                        if(data.success == "true" && user.message != undefined) {
                            const p = document.createElement('p');
                            p.className = 'another-user';
                            p.innerText = user.message;
                            body.appendChild(p);                
                        }
                    });
            };

            const back = document.querySelector('.back');
            back.onclick = () => {
                window.location.href = '/';
            };
        }

        function toContainer() {
            const p = document.createElement('p');
            p.className = 'user';
            p.innerText = value;
            body.appendChild(p);
        }

        function refresh() {
            const row = document.querySelector(".container main");
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
                    for (const data of datas) {
                        if(data.fromUser == currentUser.username) {
                            const p = document.createElement('p');
                            p.className = 'user';
                            p.innerText = data.message;
                            body.appendChild(p);
                        } else {
                            const p = document.createElement('p');
                            p.className = 'another-user';
                            p.innerText = data.message;
                            body.appendChild(p);
                        }
                    }
                    document.querySelector('.container main p:last-child').scrollIntoView(true);
                });
        }

        oldDataToContainer(document.getElementById('target').value);
        event();
    </script>
</body>
</html>