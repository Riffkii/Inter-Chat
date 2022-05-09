<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
</head>
<body>
    <h1><?= $model['h1'] ?></h1>
    <br>

    <table id="notif">
        
    </table>

    <script>
        function getData() {
            fetch('/user/notifications', {method: "GET"})
                .then(response => response.json())
                .then(datas => {
                    const table = document.getElementById('notif');
                    for (const data of datas) {
                    const message = data.message;
                    const id = data.messageFrom;
                    
                    const row = document.createElement('tr');
                    const messageData = document.createElement('td');
                    messageData.innerText = message;
                    row.appendChild(messageData);

                    const buttonData = document.createElement('td');
                    buttonData.class = 'button';
                    const button = document.createElement('input');
                    button.type = 'button';
                    button.id = id;
                    button.value = 'Accept';
                    buttonData.appendChild(button);
                    row.appendChild(buttonData);

                    table.appendChild(row);

                    return data.id;
                }
                })
                .then(id => {
                    const rows = document.querySelectorAll("td");
                    for (const row of rows) {
                        row.firstChild.onclick = function () {
                        row.firstChild.value = "Accepted";

                        //path (/user/add-friend) ngetrigger method postAddFriend
                        const request = new Request("/user/add-friend", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                target: row.firstChild.id,
                                id: id
                            })
                        });

                        fetch(request);
                        document.getElementById('notif').removeChild(row.parentNode);
                        }
                    }
                })

            
        }

        async function websocket() {
            const currentUser = await fetch('/user/username', {method: "GET"})
                .then(response => response.json())
                .then(datas => JSON.stringify(datas));

            const conn = new WebSocket("ws://localhost:3000");
            
            conn.onmessage = function(e) {
                const user = JSON.parse(e.data);
                const cUser = JSON.parse(currentUser);
                    if(cUser.username == user.target) {
                        fetch('/user/notifications', {method: "GET"})
                            .then(response => response.json())
                            .then(datas => {
                                const table = document.getElementById('notif');
                                for (const data of datas) {
                                const message = data.message;
                                const id = data.messageFrom;
                                
                                const row = document.createElement('tr');
                                const messageData = document.createElement('td');
                                messageData.innerText = message;
                                row.appendChild(messageData);

                                const buttonData = document.createElement('td');
                                buttonData.class = 'button';
                                const button = document.createElement('input');
                                button.type = 'button';
                                button.id = id;
                                button.value = 'Accept';
                                buttonData.appendChild(button);
                                row.appendChild(buttonData);

                                table.appendChild(row);

                                return data.id;
                            }
                            })
                            .then(id => {
                                const rows = document.querySelectorAll("td");
                                for (const row of rows) {
                                    row.firstChild.onclick = function () {
                                    row.firstChild.value = "Accepted";

                                    //path (/user/add-friend) ngetrigger method postAddFriend
                                    const request = new Request("/user/add-friend", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Accept": "application/json"
                                        },
                                        body: JSON.stringify({
                                            target: row.firstChild.id,
                                            id: id
                                        })
                                    });

                                    fetch(request);
                                    document.getElementById('notif').removeChild(row.parentNode);
                                    }
                                }
                            })
                    }
            };
        }

        getData();
        websocket();
    </script>
</body>
</html>