DROP DATABASE IF EXISTS FundUp;
CREATE DATABASE FundUp;
USE FundUp;

DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    username VARCHAR(30) NOT NULL,
    userPass VARCHAR(30) NOT NULL,
    fullName varchar(30),
    email varchar(30),
    phoneNum varchar(30),
    hometown VARCHAR(100),
    interests VARCHAR(100),
    setupTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (username)
);

DROP TABLE IF EXISTS UserFollow;
CREATE TABLE UserFollow (
    username VARCHAR(30) NOT NULL,
    followee VARCHAR(30) NOT NULL,
    followPostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (username , followee),
    FOREIGN KEY (username)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (followee)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Project;
CREATE TABLE Project (
    pid INT AUTO_INCREMENT NOT NULL,
    pname VARCHAR(30) NOT NULL,
    pdescription VARCHAR(500),
    pOwner varchar(30) not null,
    tags varchar(100),
    projectCreatedTime TIMESTAMP default current_timestamp not null,
    endFundTime datetime not null,
    completionDate datetime not null,
    minFund DECIMAL(10 , 2 ) NOT NULL CHECK (minFund > 0),
    maxFund DECIMAL(10 , 2 ) NOT NULL CHECK (maxFund > 0),
    fundSoFar DECIMAL(10 , 2 ) Default 0 CHECK (fundSoFar > 0),
    pstatus VARCHAR(30) NOT NULL default "Funding",
    cover LONGBLOB,
    PRIMARY KEY (pid),
    foreign key (pOwner)
		references Users(username)
        on delete cascade on update cascade
);

-- DROP TABLE IF EXISTS ProjectOwnership;
-- CREATE TABLE ProjectOwnership (
--     username VARCHAR(30) NOT NULL,
--     pid INT NOT NULL,
--     projectPostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
--     PRIMARY KEY (username , pid , projectPostedTime),
--     FOREIGN KEY (username)
--         REFERENCES Users (username)
--         ON DELETE CASCADE ON UPDATE CASCADE,
--     FOREIGN KEY (pid)
--         REFERENCES Project (pid)
--         ON DELETE CASCADE ON UPDATE CASCADE
-- );

DROP TABLE IF EXISTS ProjectDetails;
CREATE TABLE ProjectDetails (
    pid INT,
    updateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updateContent MEDIUMBLOB NOT NULL,
    updateDescription varchar(500),
    PRIMARY KEY (pid , updateTime),
    FOREIGN KEY (pid)
        REFERENCES Project (pid)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Payment;
CREATE TABLE Payment (
    username VARCHAR(30) NOT NULL,
    cardNum VARCHAR(30) NOT NULL,
    nameOnCard VARCHAR(50) NOT NULL,
    cardExp VARCHAR(10) NOT NULL,
    cardCvv VARCHAR(5) NOT NULL,
    billingAddress VARCHAR(100) NOT NULL,
    billingCity VARCHAR(30) NOT NULL,
    billingState VARCHAR(30) NOT NULL,
    billingZip VARCHAR(10) NOT NULL,
    PRIMARY KEY (username , cardNum),
    FOREIGN KEY (username)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Fund;
CREATE TABLE Fund (
    username VARCHAR(30) NOT NULL,
    pid INT NOT NULL,
    fundPostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    paymentCard VARCHAR(30) NOT NULL,
    fundAmount DECIMAL(10 , 2 ) NOT NULL CHECK (fundAmount > 0),
    moneyStatus VARCHAR(30) NOT NULL default "pending",
    PRIMARY KEY (username , pid),
    FOREIGN KEY (username)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pid)
        REFERENCES Project (pid)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (username , paymentCard)
        REFERENCES Payment (username , cardNum)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Discussion;
CREATE TABLE Discussion (
    username VARCHAR(30) NOT NULL,
    pid INT NOT NULL,
    commentPostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    aComment VARCHAR(500) NOT NULL,
    PRIMARY KEY (username , pid , commentPostedTime),
    FOREIGN KEY (username)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pid)
        REFERENCES Project (pid)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS UserLikes;
CREATE TABLE UserLikes (
    username VARCHAR(30) NOT NULL,
    pid INT NOT NULL,
    likePostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (username , pid),
    FOREIGN KEY (username)
        REFERENCES Users (username)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pid)
        REFERENCES Project (pid)
        ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS ProjectRate;
CREATE TABLE ProjectRate (
    username VARCHAR(30) NOT NULL,
    pid INT NOT NULL,
    ratePostedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    stars INT NOT NULL CHECK (stars >= 0 && stars <= 5),
    PRIMARY KEY (username , pid),
    FOREIGN KEY (username , pid)
        REFERENCES Fund (username , pid)
        ON DELETE CASCADE ON UPDATE CASCADE
);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/User.csv' INTO TABLE Users
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(username, userPass, fullName, email, hometown, interests,setupTime);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/Follow.csv' INTO TABLE UserFollow
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(userName, followee);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/Project.csv' INTO TABLE Project
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(pid, pname,pdescription, pOwner,tags,projectCreatedTime,endFundTime,completionDate,minFund, maxFund, fundSoFar, pstatus);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/projectdetail.csv' INTO TABLE ProjectDetails
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(pid,updateTime,updateContent,updateDescription);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/payments.csv' INTO TABLE Payment
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(username,cardNum,nameOnCard,cardExp,cardCvv,billingAddress,billingCity,billingState,billingZip);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/fund.csv' INTO TABLE Fund
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(username,pid,fundPostedTime,paymentCard,fundAmount,moneyStatus);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/Discuss.csv' INTO TABLE Discussion
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(username,pid,aComment);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/userlikes.csv' INTO TABLE userLikes
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(username,pid,likePostedTime);

LOAD DATA LOCAL INFILE '~/Desktop/DataSet/projectRate.csv' INTO TABLE ProjectRate
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(username,pid,ratePostedTime,stars);

