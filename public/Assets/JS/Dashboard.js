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
        })
        .then(() => {
            fetch('/user/notifications', {method: 'GET'})
                .then(response => response.json())
                .then(datas => {
                    if(datas.length > 0) {
                        const notificationC = document.querySelector('.navigation div:nth-child(1) div');
                        notificationC.className = 'visible';
                    }
                })
        });
}

async function currentUser() {
    return await fetch('/user/username', {method: "GET"})
        .then(response => response.json())
        .then(datas => JSON.stringify(datas));
}

async function websocket() {
    const currentUser = await fetch('/user/username', {method: "GET"})
    .then(response => response.json())
    .then(datas => JSON.stringify(datas));

    const conn = new WebSocket("ws://localhost:3000");
        
    conn.onopen = function(e) {
        console.log(currentUser.replace('username', 'usernameOS'));
        conn.send(currentUser.replace('username', 'usernameOS'));
    };

    conn.onmessage = function(e) {
        const user = JSON.parse(e.data);
        console.log(user.usernameOS);
        if(user.status) {
            const status = document.getElementById('status-' + user.usernameOS);
            if(user.status == 'online') {
                status.className = 'online';
                status.innerHTML = '<span>●</span>online';
            } else if(user.status == 'offline') {
                status.className = 'offline';
                status.innerHTML = '<span>●</span>offline';
            }
        } else if(user.target) {
            const cUser = JSON.parse(currentUser);
            if(cUser.username == user.target) {
                const notificationC = document.querySelector('.navigation div:nth-child(1) div');
                notificationC.className = 'visible';
            }
        }
    };
    
    const logout = document.querySelector('.navigation div:nth-child(5) ul li:last-child');
    logout.onclick = e => {
        const userData = JSON.parse(currentUser);
        conn.send(JSON.stringify({
            usernameOS: userData.username,
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

function events() {
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

    const bell = document.querySelector('.navigation div:nth-child(1)');
    bell.onclick = () => {
        window.location.href = '/user/notification';
    };
}

getData();
events();
websocket();