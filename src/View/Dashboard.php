<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter Chat</title>
    <link rel="stylesheet" href="/Assets/CSS/Reset.css">
    <link rel="stylesheet" href="/Assets/CSS/Dashboard.css">
</head>
<body>
    <div class="container">
        <header>
            <h2><?= $model['name'] ?></h2>
        </header>
        <main>
            <div class="navigation">
                <img src="/Assets/Helper/Image/bell.png" alt="notification">
                <img src="/Assets/Helper/Image/user.png" alt="user-friend">
                <img src="/Assets/Helper/Image/settings.png" alt="settings">
                <div>
                    <ul>
                        <li><a href="/user/find-friend">Search Friend</a></li>
                        <li><a href="/user/friends">Friends</a></li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li><a href="/user/profile">Profile</a></li>
                        <li><a href="/user/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
            <div class="friend">
                <input type="text" autocomplete="off" id="search" placeholder="Search Friend">
                <div><table id="friends"></table></div>
            </div>
        </main>
    </div>
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
                                        status.className = 'online';
                                        status.innerHTML = '<span>●</span>online';
                                        condition = true;
                                        break;    
                                    }
                                }
                                if(!condition) {
                                    status.className = 'offline';
                                    status.innerHTML = '<span>●</span>offline';
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
                        status.className = 'online';
                        status.innerHTML = '<span>●</span>online';
                    } else if(user.status == 'offline') {
                        const status = document.getElementById('status-' + user.username);
                        status.className = 'offline';
                        status.innerHTML = '<span>●</span>offline';
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

            const logout = document.querySelector('.navigation div:nth-child(5) ul li:last-child');
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
                                            status.className = 'online';
                                            status.innerHTML = '<span>●</span>online';
                                            condition = true;
                                            break;    
                                        }
                                    }
                                    if(!condition) {
                                        status.className = 'offline';
                                        status.innerHTML = '<span>●</span>offline';
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

            window.addEventListener('click', function(e){  
                const navs = document.querySelectorAll('img');
                const div1 = document.querySelector('.navigation div:nth-child(4)');
                const div2 = document.querySelector('.navigation div:nth-child(5)');
                for (const nav of navs) {
                    if (nav.contains(e.target)){
                        nav.classList.toggle('clicked');
                        if(nav.alt == 'user-friend') {
                            div1.classList.toggle('user-friend');
                            div2.classList.remove(nav.alt);    
                        } else if(nav.alt == 'settings') {
                            div2.classList.toggle('settings');
                            div1.classList.remove(nav.alt);
                        }
                    } else{
                        nav.classList.remove('clicked');
                        div1.classList.remove(nav.alt);
                        div2.classList.remove(nav.alt);
                    }   
                }
            });

            const bell = document.querySelector('.navigation img:nth-child(1)');
            bell.onclick = () => {
                window.location.href = '/user/notification';
            };
        }

        getData();
        websocket();
        event();
    </script>
</body>
</html>