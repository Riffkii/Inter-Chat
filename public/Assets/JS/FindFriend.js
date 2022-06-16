function sendData() {
    const conn = new WebSocket("ws://localhost:3000");
    const rows = document.querySelectorAll("td.button");
    for (const row of rows) {
        row.firstChild.onclick = function () {
            row.firstChild.value = "Sending Request";
            row.firstChild.disabled = "true";
            row.firstChild.className = "sending";

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

function events() {
    const back = document.querySelector('.back');
    back.onclick = () => {
        window.location.href = '/';
    };
}

sendData();
events();