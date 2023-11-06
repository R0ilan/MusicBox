DROP TABLE IF EXISTS REVIEWS;

CREATE TABLE REVIEWS (
    review_id INT AUTO_INCREMENT,       -- Review unique ID
    user_id INT NOT NULL,               -- Reference to reviewer's user ID
    track_spotify_id CHAR(62) NOT NULL, -- Spotify base-62 ID
    review_text VARCHAR(2000) NOT NULL, -- Review text
    review_time DATETIME NOT NULL,      -- Date and time of review
    PRIMARY KEY(review_id),
    FOREIGN KEY(user_id) REFERENCES USERS(id)
);