CREATE TABLE motion_picture (
    mpid INTEGER NOT NULL,
    name CHAR(50),
    rating FLOAT,
    production CHAR(20),
    budget INTEGER,
    PRIMARY KEY(mpid)
);

CREATE TABLE user (
    uemail CHAR(40) NOT NULL,
    name CHAR(30),
    age INTEGER,
    PRIMARY KEY(uemail)
);

CREATE TABLE likes (
    uemail CHAR(40) NOT NULL,
    mpid INT NOT NULL,
    PRIMARY KEY (uemail, mpid),
    FOREIGN KEY(uemail) REFERENCES user,
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    ON DELETE CASCADE
);

CREATE TABLE movie (
    mpid INTEGER NOT NULL,
    boxoffice_collection INTEGER,
    PRIMARY KEY(mpid),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    ON DELETE CASCADE
);

CREATE TABLE series (
    mpid INTEGER NOT NULL,
    season_count INTEGER,
    PRIMARY KEY(mpid),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    ON DELETE CASCADE
);

CREATE TABLE people (
    pid INTEGER NOT NULL,
    name CHAR(30),
    nationality CHAR(20),
    dob DATE,
    gender CHAR(20),
    PRIMARY KEY(pid)
);

CREATE TABLE role (
    mpid INTEGER,
    pid INTEGER,
    role_name CHAR(20),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    FOREIGN KEY(pid) REFERENCES people,
    ON DELETE CASCADE
);

CREATE TABLE award (
    mpid INTEGER NOT NULL,
    pid INTEGER NOT NULL,
    award_name CHAR(20) NOT NULL,
    award_year INTa NOT NULL,
    PRIMARY KEY (mpid, pid, award_name, award_year),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    FOREIGN KEY(pid) REFERENCES people,
    ON DELETE CASCADE
);

CREATE TABLE genre (
    mpid INTEGER,
    genre_name CHAR(20),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    ON DELETE CASCADE
);

CREATE TABLE location (
    mpid INTEGER NOT NULL,
    zip INTEGER NOT NULL,
    city CHAR(20),
    country CHAR(20),
    PRIMARY KEY(mpid, zip),
    FOREIGN KEY(mpid) REFERENCES motion_picture,
    ON DELETE CASCADE
);
