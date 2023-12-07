DROP TABLE IF EXISTS PLAYED_TRACKS;

CREATE TABLE PLAYED_TRACKS (
    user_id INT NOT NULL,               -- Reference to user ID
    track_spotify_id CHAR(62) NOT NULL, -- Spotify base-62 ID
    play_count INT NOT NULL DEFAULT 0,  -- Number of times the track has been played by this user
    last_played_time DATETIME NOT NULL, -- The last time the tack was played by this user
    PRIMARY KEY (user_id, track_spotify_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);