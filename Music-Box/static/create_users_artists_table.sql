DROP TABLE IF EXISTS USERS_ARTISTS;

CREATE TABLE USERS_ARTISTS (
    artist_spotify_id CHAR(62), -- Spotify base-62 ID
    user_id INT NOT NULL,       -- Reference to artists's MusicBox user ID
    PRIMARY KEY (artist_spotify_id),
    FOREIGN KEY (user_id) REFERENCES USERS(id)
);