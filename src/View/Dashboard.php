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
    <a href="/user/notification"><button id="notification">Notification</button></a>
    <a href="/user/find-friend"><button>Search Friend</button></a>
    <a href="/user/friends"><button>Friends</button></a>
    <a href="/user/profile"><button>Profile</button></a>
    <a href="/user/logout"><button id="logout">Logout</button></a>
    <br>
    <br>
    <label for="search">Search: <input type="text" autocomplete="off" id="search"></label>
    <br>
    <br>
    <table id="friends" width="200">
        
    </table>
    <script>
        function getData() {
            fetch('/user/show-friends', {method: "GET"})
                .then(response => response.json())
                .then(friends => {
                    fetch('/user/show-online-friends', {method: "GET"})
                        .then(response => response.json())
                        .then(onlineFriends => {
                            const table = document.getElementById("friends");
                            for (const friend of friends) {
                                const name = friend.name;
                                const id = friend.username;
                                
                                const row = document.createElement('tr');
                                const friendName = document.createElement('td');
                                friendName.innerText = name;
                                row.appendChild(friendName);

                                const status = document.createElement('td');
                                status.id = 'status-' + id;
                                let condition = false;
                                for (const onlineFriend of onlineFriends) {
                                    if(id == onlineFriend.username) {
                                        status.innerText = 'online';
                                        condition = true;
                                        break;    
                                    }
                                }
                                if(!condition) {
                                    status.innerText = 'offline';
                                }
                                row.appendChild(status);

                                const buttonData = document.createElement('td');
                                buttonData.className = 'button';
                                const button = document.createElement('input');
                                button.type = 'button';
                                button.id = id;
                                button.value = 'Chat';
                                buttonData.appendChild(button);
                                row.appendChild(buttonData);

                                table.appendChild(row);
                            }
                        })
                        .then(() => {
                            const rows = document.querySelectorAll("td.button");
                            for (const row of rows) {
                                row.firstChild.onclick = function () {
                                    window.location.href = '/user/chat?target=' + row.firstChild.id;
                                }
                            }
                        });
                });
        }

        async function websocket() {
            const currentUser = await fetch('/user/username', {method: "GET"})
                .then(response => response.json())
                .then(datas => JSON.stringify(datas));

            const conn = new WebSocket("ws://localhost:3000");
                
            conn.onopen = function(e) {
                conn.send(currentUser);
            };

            conn.onmessage = function(e) {
                const user = JSON.parse(e.data);
                if(user.status) {
                    if(user.status == 'online') {
                        const status = document.getElementById('status-' + user.username);
                        status.innerText = 'online';
                    } else if(user.status == 'offline') {
                        const status = document.getElementById('status-' + user.username);
                        status.innerText = 'offline';
                    }
                } else if(user.target) {
                    const cUser = JSON.parse(currentUser);
                    if(cUser.username == user.target) {
                        const notification = document.getElementById('notification');
                        const tempValue = notification.innerText;
                        notification.innerText = tempValue + '+';
                    }
                }
            };

            const logout = document.getElementById('logout');
            logout.onclick = e => {
                const userData = JSON.parse(currentUser);
                conn.send(JSON.stringify({
                    username: userData.username,
                    status: 'offline'
                }));
            };
        }

        function refresh() {
            const row = document.getElementById("friends")
            while (row.firstChild) {
                row.removeChild(row.firstChild);
            }
        }

        function event() {
            const search = document.getElementById('search');
            search.onkeyup = function() {
                const key = search.value;
                fetch('/user/search-friend?friend=' + key, {method: "GET"})
                    .then(response => response.json())
                    .then(friends => {
                        fetch('/user/show-online-friends', {method: "GET"})
                            .then(response => response.json())
                            .then(onlineFriends => {
                                refresh();
                                const table = document.getElementById("friends");
                                for (const friend of friends) {
                                    const name = friend.name;
                                    const id = friend.username;
                                    
                                    const row = document.createElement('tr');
                                    const friendName = document.createElement('td');
                                    friendName.innerText = name;
                                    row.appendChild(friendName);

                                    const status = document.createElement('td');
                                    status.id = 'status-' + id;
                                    let condition = false;
                                    for (const onlineFriend of onlineFriends) {
                                        if(id == onlineFriend.username) {
                                            status.innerText = 'online';
                                            condition = true;
                                            break;    
                                        }
                                    }
                                    if(!condition) {
                                        status.innerText = 'offline';
                                    }
                                    row.appendChild(status);

                                    const buttonData = document.createElement('td');
                                    buttonData.className = 'button';
                                    const button = document.createElement('input');
                                    button.type = 'button';
                                    button.id = id;
                                    button.value = 'Chat';
                                    buttonData.appendChild(button);
                                    row.appendChild(buttonData);

                                    table.appendChild(row);
                                }
                            });
                    });
            }
        }

        getData();
        websocket();
        event();
    </script>
</body>
</html>