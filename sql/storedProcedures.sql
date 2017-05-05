USE FundUp;
-- Write queries for the end of a funding campaign. E.g., you could use triggers to detect when a campaign is fully funded or time is up; if successfully funded, generate charges to sponsors’ credit card
drop event if exists DailyCheck;
delimiter //
create event DailyCheck
  on schedule every 1 day
do
  update Project
  set pstatus = 'Discard'
  where pstatus = 'Funding' and fundSoFar < minFund and date(endFundTime) < curdate();

  update Fund
  set moneyStatus = 'Returned'
  where moneyStatus = 'Pending' and pid in (select pid from Project where pstatus = 'Discard');

  update Project
  set pstatus = 'Funded, in progessing'
  where pstatus = 'Funding' and fundSoFar >= minFund and date(endFundTime) < curdate();

  Update Fund
  set moneyStatus = 'Released'
  where moneyStatus = 'Pending' and pid in (select pid from Project where pstatus = 'Funded, in progessing');
delimiter ;

drop trigger if exists EnoughFundForProject;
delimiter //
create trigger EnoughFundForProject
before update on Project
for each row
  begin
    if new.pstatus != 'Completed' and new.fundSoFar >= new.maxFund
    then set new.pstatus = 'Funded, in progessing';
      update Fund F
      set moneyStatus = 'Released'
      where F.pid = new.pid;
    end if;
  end //
delimiter ;

DROP PROCEDURE IF EXISTS CreateUser;
DELIMITER //
CREATE PROCEDURE CreateUser(IN userNameIn VARCHAR(30), IN userPassIn varchar(30), IN fullNameIn varchar(30),
                            IN emailIn varchar(30), IN phoneIn varchar(30))
  BEGIN
    insert into Users(username, userpass, fullName, email, phoneNum) values (userNameIn, userPassIn, fullNameIn, emailIn, phoneIn);
  END //
DELIMITER ;

-- Insert a new project for a particular user, with a name, description, and other needed info.
DROP PROCEDURE IF EXISTS CreateProject;
DELIMITER //
CREATE PROCEDURE CreateProject(
  IN usernameIn VARCHAR(30),
  IN pnameIn VARCHAR(30),
  IN pdescriptionIn varchar(500),
  IN tagsIn varchar(100),
  IN endFundTimeIn datetime,
  IN completionDateIn datetime,
  IN minFundIn decimal(10, 2),
  IN maxFundIn decimal(10, 2),
  IN media LONGBLOB)
  BEGIN
    insert into Project(pname, pdescription, pOwner, tags, endFundTime, completionDate, minFund, maxFund, cover, pstatus)
    values (pnameIn, pdescriptionIn, usernameIn, tagsIn, endFundTimeIn, completionDateIn,
            minFundIn, maxFundIn, media, 'Funding');
  END //
DELIMITER ;

-- Insert a pledge to sponsor a project, for a particular user, project, and amount.
DROP PROCEDURE IF EXISTS CreateFund;
DELIMITER //
CREATE PROCEDURE CreateFund(IN usernameIn VARCHAR(30),
                            IN pidIn int, IN fundAmountIn Decimal(10, 2), IN paymentCardIn varchar(30), OUT actualFunded decimal(10, 2))
  BEGIN
    set @exceedFundAmount = (select  (fundSoFar + fundAmountIn) - P.maxFund from Project P where P.pid = pidIn);
    if @exceedFundAmount > 0
    then set fundAmountIn = fundAmountIn - @exceedFundAmount;
    end if;

    insert into Fund (username, pid, fundAmount, paymentCard)
    values (usernameIn, pidIn, fundAmountIn, paymentCardIn);

    update Project
    set fundSoFar = fundSoFar + fundAmountIn
    where pid = pidIn;

    set actualFunded = fundAmountIn;
  END //
DELIMITER ;

drop procedure if exists validateUser;
delimiter //
create procedure validateUser(IN userNameIn varchar(30), IN userPassIn varchar(30), OUT validity BOOLEAN)
  BEGIN
    set validity = (select userPass = userPassIn from Users where username = userNameIn);
  END //
DELIMITER ;