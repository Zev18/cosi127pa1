CREATE TABLE MotionPicture (
    id INTEGER NOT NULL,
    name CHAR(20),
    rating FLOAT,
    production CHAR(20),
    budget INTEGER,
    PRIMARY KEY(mp_id)
);

CREATE TABLE User (
    email CHAR(30) NOT NULL,
    name CHAR(20),
    age INTEGER,
    PRIMARY KEY(email)
);

CREATE TABLE Likes (
    uemail INT NOT NULL,
    mpid INT NOT NULL,
    PRIMARY KEY (uemail, mpid),
    FOREIGN KEY(uemail) REFERENCES User,
    FOREIGN KEY(mpid) REFERENCES MotionPicture
);

CREATE TABLE Movie (
    mpid INTEGER NOT NULL,
    boxoffice_collection INTEGER,
    PRIMARY KEY(mpid),
    FOREIGN KEY(mpid) REFERENCES MotionPicture
);

CREATE TABLE Series (
    mpid INTEGER NOT NULL,
    season_count INTEGER,
    PRIMARY KEY(mpid),
    FOREIGN KEY(mpid) REFERENCES MotionPicture
);

CREATE TABLE People (
    id INTEGER NOT NULL,
    name CHAR(20),
    nationality CHAR(20),
    dob DATE,
    gender CHAR(20),
    PRIMARY KEY(id)
);

CREATE TABLE Role (
    mpid INTEGER,
    pid INTEGER,
    role_name CHAR(20),
    FOREIGN KEY(mpid) REFERENCES MotionPicture,
    FOREIGN KEY(pid) REFERENCES People
);

CREATE TABLE Award (
    mpid INTEGER NOT NULL,
    pid INTEGER NOT NULL,
    award_name CHAR(20) NOT NULL,
    award_year CHAR(20) NOT NULL,
    PRIMARY KEY (mpid, pid, award_name, award_year),
    FOREIGN KEY(mpid) REFERENCES MotionPicture,
    FOREIGN KEY(pid) REFERENCES People
);

CREATE TABLE Genre (
    mpid INTEGER,
    genre_name CHAR(20),
    FOREIGN KEY(mpid) REFERENCES MotionPicture
);

CREATE TABLE Location (
    mpid INTEGER NOT NULL,
    zip INTEGER,
    city CHAR(20),
    country CHAR(20),
    PRIMARY KEY(mpid, zip),
    FOREIGN KEY(mpid) REFERENCES MotionPicture,
    ON DELETE CASCADE
);


