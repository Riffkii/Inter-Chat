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
)