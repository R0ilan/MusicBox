DROP TABLE IF EXISTS FRIENDS;

CREATE TABLE FRIENDS (
    user_id INT,
    friend_user_id INT,
    PRIMARY KEY (user_id, friend_user_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id),
    FOREIGN KEY (friend_user_id) REFERENCES USERS(id)
);