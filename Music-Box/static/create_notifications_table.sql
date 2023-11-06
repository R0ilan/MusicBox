DROP TABLE IF EXISTS NOTIFICATIONS;

CREATE TABLE NOTIFICATIONS (
    notification_id INT AUTO_INCREMENT,     -- Notification unique ID
    user_id INT NOT NULL,                   -- Reference to user ID
    notification_text VARCHAR(100),         -- Notification message
    notification_time DATETIME NOT NULL,    -- Date and time of notification
    PRIMARY KEY (notification_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);