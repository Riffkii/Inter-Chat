const conn = new WebSocket("ws://localhost:3000");
const chatArea = document.querySelector(".container main .chat");
const newMessage = document.querySelector(".container main p");
let value = null;

function events() {
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
        if (conn.readyState === WebSocket.OPEN) {
            conn.send(JSON.stringify({
                target: document.getElementById('target').value,
                message: document.getElementById('input').value
            }));
        }
        document.getElementById("form").reset();
        document.querySelector('.container main .chat p:last-child').scrollIntoView(true);
    };

    conn.onmessage = function (e) {
        const user = JSON.parse(e.data);
        console.log('test');
        fetch('/user/check-friend?target=' + document.getElementById('target').value, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success == "true" && user.message != undefined) {
                    const p = document.createElement('p');
                    p.className = 'another-user';
                    p.innerText = user.message;
                    chatArea.appendChild(p);
                }
                newMessage.className = 'visible';
            });
    };

    const back = document.querySelector('.back');
    back.onclick = () => {
        window.location.href = '/';
    };

    newMessage.onclick = () => {
        newMessage.className = 'in';
        document.querySelector('.container main .chat p:last-child').scrollIntoView(true);
    };

    chatArea.addEventListener('wheel', () => {
        const revealTop = document.querySelector('.chat p:last-child').getBoundingClientRect().top;
        if (Math.round(revealTop) <= 556) {
            newMessage.className = 'in';
        }
    });
}

function toContainer() {
    const p = document.createElement('p');
    p.className = 'user';
    p.innerText = value;
    chatArea.appendChild(p);
}

function refresh() {
    const row = document.querySelector(".container main .chat");
    while (row.firstChild) {
        row.removeChild(row.firstChild);
    }
}

async function oldDataToContainer(target) {
    refresh();
    const currentUser = await fetch('/user/username', { method: "GET" })
        .then(response => response.json());

    fetch('/user/message?target=' + target, { method: 'GET' })
        .then(response => response.json())
        .then(datas => {
            for (const data of datas) {
                if (data.fromUser == currentUser.username) {
                    const p = document.createElement('p');
                    p.className = 'user';
                    p.innerText = data.message;
                    chatArea.appendChild(p);
                } else {
                    const p = document.createElement('p');
                    p.className = 'another-user';
                    p.innerText = data.message;
                    chatArea.appendChild(p);
                }
            }
            document.querySelector('.container main .chat p:last-child').scrollIntoView(true);
        });
}

oldDataToContainer(document.getElementById('target').value);
events();