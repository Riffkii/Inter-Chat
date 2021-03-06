function getData() {
    fetch('/user/notifications', {method: "GET"})
        .then(response => response.json())
        .then(datas => {
            const table = document.getElementById('notif');
            const ids = [];
            for (const data of datas) {
                const message = data.message;
                const id = data.messageFrom;
                
                const row = document.createElement('tr');
                const messageData = document.createElement('td');
                messageData.innerText = message;
                row.appendChild(messageData);

                const buttonData = document.createElement('td');
                buttonData.className = 'button';
                const button = document.createElement('input');
                button.type = 'button';
                button.id = id;
                button.value = 'Accept';
                buttonData.appendChild(button);
                row.appendChild(buttonData);

                table.appendChild(row);

                ids.push(data.id);
            }

            return ids;
        })
        .then(ids => {
            let counter = 0;
            const rows = document.querySelectorAll(".button");
            for (const row of rows) {
                const id = ids[counter];

                row.firstChild.onclick = function () {
                    fetch("/user/add-friend", {
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
                    document.getElementById('notif').removeChild(row.parentNode);
                }
                counter++;
            }
        });

    
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
                        const ids = [];
                        for (const data of datas) {
                            const message = data.message;
                            const id = data.messageFrom;
                            
                            const row = document.createElement('tr');
                            const messageData = document.createElement('td');
                            messageData.innerText = message;
                            row.appendChild(messageData);

                            const buttonData = document.createElement('td');
                            buttonData.className = 'button';
                            const button = document.createElement('input');
                            button.type = 'button';
                            button.id = id;
                            button.value = 'Accept';
                            buttonData.appendChild(button);
                            row.appendChild(buttonData);

                            table.appendChild(row);

                            ids.push(data.id);
                        }

                        return ids;
                    })
                    .then(ids => {
                        let counter = 0;
                        const rows = document.querySelectorAll(".button");
                        for (const row of rows) {
                            const id = ids[counter];

                            row.firstChild.onclick = function () {
                                fetch("/user/add-friend", {
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
                                document.getElementById('notif').removeChild(row.parentNode);
                            }
                            counter++;
                        }
                    });
            }
    };
}

function events() {
    const back = document.querySelector('.back');
    back.onclick = () => {
        window.location.href = '/';
    };
}

getData();
websocket();
events();