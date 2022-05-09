CREATE TABLE users (
    username VARCHAR(30) PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE sessions (
    id VARCHAR(100) PRIMARY KEY,
    user_username VARCHAR(30) NOT NULL,
    CONSTRAINT fk_sessions_users
        FOREIGN KEY (user_username) 
            REFERENCES users (username)
);

CREATE TABLE friendships (
    user_1_username VARCHAR(30) NOT NULL,
    user_2_username VARCHAR(30) NOT NULL
    PRIMARY KEY(user_1, user_2),
    CONSTRAINT fk_friendships_users_1
        FOREIGN KEY (user_1_username) 
            REFERENCES users (username),
    CONSTRAINT fk_friendships_users_2
        FOREIGN KEY (user_2_username) 
            REFERENCES users (username)
);

CREATE TABLE notifications (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    message_from VARCHAR(50) NOT NULL,
    message VARCHAR(100),
    CONSTRAINT fk_notifications_users
        FOREIGN KEY (username) 
            REFERENCES users (username)
);