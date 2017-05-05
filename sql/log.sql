USE FundUp;
drop table if EXISTS Log;
create table Log(
  username varchar(30),
  operation varchar(30),
  target varchar(30),
  logTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (username, logTime),
  FOREIGN KEY (username) REFERENCES Users(username)
)