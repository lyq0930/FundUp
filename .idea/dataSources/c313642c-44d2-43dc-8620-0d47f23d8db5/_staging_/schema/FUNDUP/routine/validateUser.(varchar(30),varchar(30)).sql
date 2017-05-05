CREATE PROCEDURE validateUser(IN userNameIn VARCHAR(30), IN userPassIn VARCHAR(30), OUT validity TINYINT(1))
  BEGIN
    set validity = (select userPass = userPassIn from Users where username = userNameIn);
  END;