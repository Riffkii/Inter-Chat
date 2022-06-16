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

                        const buttonData = document.createElement('td');
                        buttonData.className = 'button';
                        const button = document.createElement('input');
                        button.type = 'button';
                        button.id = id;
                        button.value = 'Unfriend';
                        buttonData.appendChild(button);
                        row.appendChild(buttonData);

                        table.appendChild(row);
                    }
                    unfriend();
                });
        });
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

                            const buttonData = document.createElement('td');
                            buttonData.className = 'button';
                            const button = document.createElement('input');
                            button.type = 'button';
                            button.id = id;
                            button.value = 'Unfriend';
                            buttonData.appendChild(button);
                            row.appendChild(buttonData);

                            table.appendChild(row);
                        }
                        unfriend();
                    });
            });
    }

    const back = document.querySelector('.back');
    back.onclick = () => {
        window.location.href = '/';
    };
}

function unfriend() {
    console.log('as');
    const rows = document.querySelectorAll("td.button");
    console.log(rows);
    for (const row of rows) {
        row.firstChild.onclick = function () {
            fetch('/user/unfriend', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    target: row.firstChild.id ,
                })
            });

            document.getElementById('friends').removeChild(row.parentNode);
        }
    }
}

getData();
events();